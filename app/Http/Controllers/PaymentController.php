<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->with(['booking.client'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $invoiceId = $request->query('invoice_id');

        $invoice = null;
        if ($invoiceId) {
            $invoice = \App\Models\Invoice::with('booking.client')->findOrFail($invoiceId);
            $bookingId = $invoice->booking_id; // подставляем бронь из счета
        }

        $bookings = Booking::with('client')
            ->orderBy('id', 'desc')
            ->limit(200)
            ->get();

        return view('payments.create', compact('bookings', 'bookingId', 'invoiceId', 'invoice'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        if (!empty($data['invoice_id'])) {
            $invoice = \App\Models\Invoice::findOrFail($data['invoice_id']);
            $data['booking_id'] = $invoice->booking_id;
        }

        $payment = Payment::create($data);

        logAudit('created', $payment, null, $payment->toArray());

        // Обновим статус счета после оплаты
        if (!empty($payment->invoice_id)) {
            $this->recalcInvoiceStatus(\App\Models\Invoice::find($payment->invoice_id));
        }

        return redirect()->route('payments.index')
            ->with('success', 'Оплата добавлена');

        if (!empty($data['invoice_id'])) {
        $invoice = Invoice::find($data['invoice_id']);
        if ($invoice) {
            app(InvoiceService::class)->refreshStatus($invoice);
        }
}
    }

    private function recalcInvoiceStatus(?\App\Models\Invoice $invoice): void
    {
        if (!$invoice) return;

        $paid = (float)$invoice->payments()->sum('amount');
        $due  = (float)$invoice->total;

        if ($paid <= 0) {
            $status = 'unpaid';
        } elseif ($paid + 0.01 < $due) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        $invoice->update(['status' => $status]);
    }

    public function destroy(Payment $payment)
    {
        $invoiceId = $payment->invoice_id;

        $old = $payment->toArray();

        $payment->delete();

        logAudit('deleted', $payment, $old, null);

        if ($invoiceId) {
            $this->recalcInvoiceStatus(\App\Models\Invoice::find($invoiceId));
        }

        return redirect()->route('payments.index')
            ->with('success', 'Оплата удалена');
        
        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                app(InvoiceService::class)->refreshStatus($invoice);
            }
        }
    }
}
