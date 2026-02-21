<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
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
        $invoices = Invoice::query()
            ->with(['booking.client', 'payments'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $bookings = Booking::query()
            ->with(['client', 'room'])
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->whereDoesntHave('invoice')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('invoices.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $prefix = $this->routePrefix();

        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['room', 'client', 'services', 'invoice'])->findOrFail($data['booking_id']);

        if ($booking->invoice) {
            return redirect()
                ->route($prefix.'invoices.show', $booking->invoice)
                ->with('success', 'Счёт уже существует, открываю его');
        }

        $invoice = DB::transaction(function () use ($booking) {

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'number'     => 'INV-' . now()->format('Ymd') . '-' . str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT),
                'issued_at'  => now()->toDateString(),
                'due_at'     => now()->addDays(3)->toDateString(),
                'status'     => 'unpaid',
                'total'      => 0,
                'note'       => 'Счёт создан вручную',
            ]);

            // 1) Проживание
            $nights    = max(1, $booking->date_from->diffInDays($booking->date_to));
            $unitPrice = (float)($booking->room->price_per_night ?? 0);
            $lineTotal = $nights * $unitPrice;

            $txt = 'Проживание (номер №' . ($booking->room->number ?? '—') . ')';

            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'type'        => 'stay',
                'title'       => $txt,
                'description' => $txt,
                'quantity'    => $nights,
                'unit_price'  => $unitPrice,
                'total'       => $lineTotal,
            ]);

            // 2) Услуги
            foreach ($booking->services as $service) {
                $qty = (int)($service->pivot->quantity ?? 0);
                if ($qty <= 0) continue;

                $unit = (float)($service->pivot->price_snapshot ?? $service->price ?? 0);
                $line = $qty * $unit;

                $txt = 'Услуга: ' . $service->name;

                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'type'        => 'service',
                    'title'       => $txt,
                    'description' => $txt,
                    'quantity'    => $qty,
                    'unit_price'  => $unit,
                    'total'       => $line,
                ]);
            }

            // итог
            $invoice->update([
                'total' => $invoice->items()->sum('total'),
            ]);

            return $invoice;
        });

        logAudit('created', $invoice, null, $invoice->toArray());

        return redirect()
            ->route($prefix.'invoices.show', $invoice)
            ->with('success', 'Счёт создан');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['booking.client', 'booking.room', 'items', 'payments']);

        logAudit('viewed', $invoice, null, null);

        return view('invoices.show', compact('invoice'));
    }
}