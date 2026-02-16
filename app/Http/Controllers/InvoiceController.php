<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::query()
            ->with(['booking.client'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['booking.client', 'booking.room', 'items', 'payments']);

        logAudit('viewed', $invoice, null, null);

        return view('invoices.show', compact('invoice'));
    }

    // остальное позже (edit/update/destroy)
}
