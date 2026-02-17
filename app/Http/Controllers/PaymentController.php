<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->with(['invoice.booking.client'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     *  Только через invoice_id
     * /payments/create?invoice_id=123
     */
    public function create(Request $request)
    {
        $invoiceId = $request->query('invoice_id');

        if (!$invoiceId) {
            return redirect()->route('invoices.index')
                ->with('success', 'Оплату можно добавить только из счёта. Откройте счёт и нажмите “Добавить оплату”.');
        }

        $invoice = Invoice::with(['booking.client'])->findOrFail($invoiceId);

        return view('payments.create', [
            'invoice'   => $invoice,
            'invoiceId' => $invoice->id,
            'bookingId' => $invoice->booking_id,
        ]);
    }

    /**
     *  Создание оплаты строго с invoice_id
     * Автоматика:
     * - пересчитать статус счета (unpaid/partial/paid)
     * - если paid → booking confirmed (если pending)
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $invoice = Invoice::with(['booking'])->findOrFail($data['invoice_id']);

        // 🔒 booking_id НЕ берем из формы — жестко от счета
        $data['booking_id'] = $invoice->booking_id;

        $payment = Payment::create($data);
        logAudit('created', $payment, null, $payment->toArray());

        // Пересчет статуса счета
        $oldInvoice = $invoice->toArray();
        $status = $this->recalcInvoiceStatus($invoice);

        if (($oldInvoice['status'] ?? null) !== $invoice->fresh()->status) {
            logAudit('updated', $invoice->fresh(), $oldInvoice, $invoice->fresh()->toArray());
        }

        // Если счет полностью оплачен → бронь confirmed (если была pending)
        if ($status === 'paid') {
            $booking = $invoice->booking()->first();
            if ($booking && $booking->status === 'pending') {
                $oldBooking = $booking->toArray();
                $booking->update(['status' => 'confirmed']);
                logAudit('updated', $booking, $oldBooking, $booking->fresh()->toArray());
            }
        }

        //  логично возвращать на сам счет
        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Оплата добавлена');
    }

    public function destroy(Payment $payment)
    {
        // payment должен быть привязан к счету (invoice-only логика)
        $invoice = null;
        if (!empty($payment->invoice_id)) {
            $invoice = Invoice::with('booking')->find($payment->invoice_id);
        }

        $old = $payment->toArray();
        $payment->delete();
        logAudit('deleted', $payment, $old, null);

        if ($invoice) {
            $oldInvoice = $invoice->toArray();
            $this->recalcInvoiceStatus($invoice);

            if (($oldInvoice['status'] ?? null) !== $invoice->fresh()->status) {
                logAudit('updated', $invoice->fresh(), $oldInvoice, $invoice->fresh()->toArray());
            }

            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'Оплата удалена');
        }

        return redirect()->route('payments.index')
            ->with('success', 'Оплата удалена');
    }

    /**
     * unpaid | partial | paid
     * closed тут НЕ трогаем — его ставим при check_out
     */
    private function recalcInvoiceStatus(Invoice $invoice): string
    {
        if ($invoice->status === 'closed') {
            return 'closed';
        }

        $paid = (float)$invoice->payments()->sum('amount');
        $due  = (float)$invoice->total;

        if ($due <= 0) {
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
