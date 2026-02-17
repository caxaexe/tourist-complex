<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->with(['booking.client', 'invoice'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $invoiceId = $request->query('invoice_id');

        $invoice = null;
        if ($invoiceId) {
            $invoice = Invoice::with(['booking.client'])->findOrFail($invoiceId);
            $bookingId = $invoice->booking_id; // подставляем бронь из счета
        }

        $bookings = Booking::with('client')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('payments.create', compact('bookings', 'bookingId', 'invoiceId', 'invoice'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        // paid_at по умолчанию
        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        // если оплату добавляют со страницы счета — привязываем booking_id автоматически
        $invoice = null;
        if (!empty($data['invoice_id'])) {
            $invoice = Invoice::with(['booking'])->findOrFail($data['invoice_id']);
            $data['booking_id'] = $invoice->booking_id;
        }

        $payment = Payment::create($data);
        logAudit('created', $payment, null, $payment->toArray());

        // если оплата по счету — пересчитать статус счета + возможно подтвердить бронь
        if ($invoice) {
            $oldInvoice = $invoice->toArray();

            $status = $this->recalcInvoiceStatus($invoice);

            // логируем только если реально поменялся статус
            if (($oldInvoice['status'] ?? null) !== $invoice->fresh()->status) {
                logAudit('updated', $invoice->fresh(), $oldInvoice, $invoice->fresh()->toArray());
            }

            // если счет полностью оплачен → бронь confirmed (если была pending)
            if ($status === 'paid') {
                $booking = $invoice->booking()->first();
                if ($booking && $booking->status === 'pending') {
                    $oldBooking = $booking->toArray();
                    $booking->update(['status' => 'confirmed']);
                    logAudit('updated', $booking, $oldBooking, $booking->fresh()->toArray());
                }
            }
        }

        return redirect()->route('payments.index')
            ->with('success', 'Оплата добавлена');
    }

    public function destroy(Payment $payment)
    {
        $invoice = null;
        if (!empty($payment->invoice_id)) {
            $invoice = Invoice::with('booking')->find($payment->invoice_id);
        }

        $old = $payment->toArray();
        $payment->delete();
        logAudit('deleted', $payment, $old, null);

        // после удаления оплаты пересчитываем статус счета
        if ($invoice) {
            $oldInvoice = $invoice->toArray();

            $status = $this->recalcInvoiceStatus($invoice);

            if (($oldInvoice['status'] ?? null) !== $invoice->fresh()->status) {
                logAudit('updated', $invoice->fresh(), $oldInvoice, $invoice->fresh()->toArray());
            }

            // при удалении оплаты НЕ откатываем бронь обратно в pending автоматически
            // иначе можно случайно ломать уже заселенных гостей.
        }

        return redirect()->route('payments.index')
            ->with('success', 'Оплата удалена');
    }

    /**
     * Пересчитывает статус счета по сумме оплат:
     * unpaid | partial | paid
     * (closed не трогаем тут — он ставится при check_out)
     * Возвращает вычисленный статус.
     */
    private function recalcInvoiceStatus(Invoice $invoice): string
    {
        // если счет уже закрыт — не трогаем
        if ($invoice->status === 'closed') {
            return 'closed';
        }

        $paid = (float) $invoice->payments()->sum('amount');
        $due  = (float) $invoice->total;

        if ($due <= 0) {
            // на всякий случай: если total = 0, считаем paid
            $status = 'paid';
        } elseif ($paid <= 0) {
            $status = 'unpaid';
        } elseif ($paid + 0.01 < $due) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        $invoice->update(['status' => $status]);

        return $status;
    }
}
