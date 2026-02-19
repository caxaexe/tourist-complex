<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class BookingInvoiceController extends Controller
{
    public function store(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->withErrors(['invoice' => 'Нельзя создать счёт для отменённого бронирования.']);
        }

        $invoice = DB::transaction(function () use ($booking) {

            $booking->load(['client', 'room', 'services']);

            $number = 'INV-' . now()->format('Ymd') . '-' . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'number'     => $number,
                'issued_at'  => now()->toDateString(),
                'due_at'     => now()->addDays(3)->toDateString(),
                'status'     => 'issued',
                'total'      => 0,
                'note'       => null,
            ]);

            // 1) Проживание
            $nights    = max(1, $booking->date_from->diffInDays($booking->date_to));
            $unitPrice = (float)($booking->room->price_per_night ?? 0);
            $lineTotal = $nights * $unitPrice;

            $txt = 'Проживание: номер ' . $booking->room->number;

            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'type'        => 'stay',
                'title'       => $txt,
                'description' => $txt,
                'quantity'    => $nights,
                'unit_price'  => $unitPrice,
                'total'       => $lineTotal,
            ]);

            // 2) Услуги (pivot.price_snapshot)
            foreach ($booking->services as $service) {
                $qty = (int)($service->pivot->quantity ?? 0);
                if ($qty <= 0) continue;

                $unit = (float)($service->pivot->price_snapshot ?? $service->price ?? 0);
                $line = $qty * $unit;

                $txt = $service->name;

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

            // Итог по счету — только из items
            $invoice->update([
                'total' => $invoice->items()->sum('total'),
            ]);

            return $invoice;
        });

        logAudit('created', $invoice, null, $invoice->toArray());

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Счёт создан');
    }
}
