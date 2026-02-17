<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceService
{
    public function refreshStatus(Invoice $invoice): void
    {
        $invoice->loadMissing('payments');

        $paid = (float)$invoice->payments->sum('amount');
        $total = (float)$invoice->total;

        $newStatus = 'issued';

        if ($paid <= 0) {
            $newStatus = 'issued';
        } elseif ($paid + 0.01 < $total) {
            $newStatus = 'partial';
        } else {
            $newStatus = 'paid';
        }

        if ($invoice->status !== $newStatus) {
            $old = $invoice->toArray();
            $invoice->update(['status' => $newStatus]);
            logAudit('updated', $invoice, $old, $invoice->fresh()->toArray());
        }
    }
}
