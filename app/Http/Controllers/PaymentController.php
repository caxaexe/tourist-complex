<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private function routePrefix(): string
{
    $u = auth()->user();
    if ($u?->hasRole('admin')) return 'admin.';
    if ($u?->hasRole('employee')) return 'staff.';
    return '';
}

    public function index()
    {
        $payments = Payment::query()
            ->with(['invoice.booking.client'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $prefix = $this->routePrefix();

        $invoiceId = $request->query('invoice_id');

        if (!$invoiceId) {
            return redirect()->route($prefix.'invoices.index')
                ->with('success', 'Оплату можно добавить только из счёта. Откройте счёт и нажмите “Добавить оплату”.');
        }

        $invoice = Invoice::with(['booking.client'])->findOrFail($invoiceId);

        return view('payments.create', [
            'invoice'   => $invoice,
            'invoiceId' => $invoice->id,
            'bookingId' => $invoice->booking_id,
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $prefix = $this->routePrefix();

        $data = $request->validated();

        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $invoice = Invoice::with(['booking'])->findOrFail($data['invoice_id']);

        $data['booking_id'] = $invoice->booking_id;

        $payment = Payment::create($data);
        logAudit('created', $payment, null, $payment->toArray());

        $oldInvoice = $invoice->toArray();
        $status = $this->recalcInvoiceStatus($invoice);

        if (($oldInvoice['status'] ?? null) !== $invoice->fresh()->status) {
            logAudit('updated', $invoice->fresh(), $oldInvoice, $invoice->fresh()->toArray());
        }

        if ($status === 'paid') {
            $booking = $invoice->booking()->first();
            if ($booking && $booking->status === 'pending') {
                $oldBooking = $booking->toArray();
                $booking->update(['status' => 'confirmed']);
                logAudit('updated', $booking, $oldBooking, $booking->fresh()->toArray());
            }
        }

        $prefix = $this->routePrefix();

        return redirect()
            ->route($prefix.'invoices.show', $invoice)
            ->with('success', 'Оплата добавлена');

    }

    public function destroy(Payment $payment)
    {
        $prefix = $this->routePrefix();

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
                ->route($prefix.'invoices.show', $invoice)
                ->with('success', 'Оплата удалена');
        }

        $prefix = $this->routePrefix();

        return redirect()
            ->route($prefix.'invoices.show', $invoice)
            ->with('success', 'Оплата удалена');
    }

    private function recalcInvoiceStatus(Invoice $invoice): string
    {
        if ($invoice->status === 'closed') {
            return 'closed';
        }

        $paid = (float) $invoice->payments()->sum('amount');
        $due  = (float) $invoice->total;

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