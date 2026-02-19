<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Booking;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::query()
            ->with(['booking.client', 'payments'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    // ✅ Страница создания счета (выбираем бронь)
    public function create()
    {
        $bookings = Booking::query()
            ->with(['client', 'room'])
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->whereDoesntHave('invoice') // только те, у кого нет счета
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('invoices.create', compact('bookings'));
    }

    // ✅ Создание счета по выбранной брони
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['room', 'client', 'services'])->findOrFail($data['booking_id']);

        // защита: не создавать второй счет
        if ($booking->invoice) {
            return redirect()->route('invoices.show', $booking->invoice)
                ->with('success', 'Счёт уже существует, открываю его');
        }

        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'number'     => 'INV-' . now()->format('Ymd') . '-' . str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT),
            'issued_at'  => now()->toDateString(),
            'due_at'     => now()->addDays(3)->toDateString(),
            'status'     => 'unpaid',
            'total'      => 0,
            'note'       => 'Счёт создан вручную',
        ]);

        $itemsTotal = 0;

        // проживание
        $nights = max(1, $booking->date_from->diffInDays($booking->date_to));
        $stayPrice = (float)($booking->room->price_per_night ?? 0);
        $stayLine  = $nights * $stayPrice;

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'type'       => 'stay',
            'title'      => 'Проживание (номер №' . ($booking->room->number ?? '—') . ')',
            'quantity'   => $nights,
            'unit_price' => $stayPrice,
            'line_total' => $stayLine,
        ]);

        $itemsTotal += $stayLine;

        // услуги
        foreach ($booking->services as $service) {
            $qty = (int)($service->pivot->quantity ?? 0);
            if ($qty <= 0) continue;

            $unit = (float)($service->pivot->price ?? $service->price ?? 0);
            $line = $qty * $unit;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type'       => 'service',
                'title'      => 'Услуга: ' . $service->name,
                'quantity'   => $qty,
                'unit_price' => $unit,
                'line_total' => $line,
            ]);

            $itemsTotal += $line;
        }

        $invoice->update(['total' => $itemsTotal]);

        logAudit('created', $invoice, null, $invoice->toArray());

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Счёт создан');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['booking.client', 'booking.room', 'items', 'payments']);

        logAudit('viewed', $invoice, null, null);

        return view('invoices.show', compact('invoice'));
    }
}
