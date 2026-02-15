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
        // Нельзя создавать счет для отмененной брони (по желанию)
        if ($booking->status === 'cancelled') {
            return back()->withErrors(['invoice' => 'Нельзя создать счёт для отменённого бронирования.']);
        }

        // Если хочешь запретить 2 счета на одно бронирование, раскомментируй:
        // if ($booking->invoices()->exists()) {
        //     return back()->withErrors(['invoice' => 'Счёт уже существует для этого бронирования.']);
        // }

        $invoice = DB::transaction(function () use ($booking) {

            $booking->load(['client', 'room', 'services']);

            $issuedAt = now()->toDateString();
            $dueAt = now()->addDays(3)->toDateString();

            // Номер счета (простой вариант)
            $number = 'INV-' . now()->format('Ymd') . '-' . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'number' => $number,
                'issued_at' => $issuedAt,
                'due_at' => $dueAt,
                'status' => 'issued', // issued | paid | cancelled
                'total' => 0,
                'note' => null,
            ]);

            $itemsTotal = 0;

            // 1) Проживание
            $nights = $booking->date_from->diffInDays($booking->date_to);
            $unit = (float)$booking->room->price_per_night;
            $line = $nights * $unit;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => 'stay',
                'title' => 'Проживание: номер ' . $booking->room->number,
                'quantity' => $nights,
                'unit_price' => $unit,
                'line_total' => $line,
            ]);

            $itemsTotal += $line;

            // 2) Услуги (берем snapshot price из pivot booking_service)
            foreach ($booking->services as $service) {
                $qty = (int)$service->pivot->quantity;
                $unit = (float)$service->pivot->price;
                $line = $qty * $unit;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'service',
                    'title' => $service->name,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $line,
                ]);

                $itemsTotal += $line;
            }

            $invoice->update(['total' => $itemsTotal]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Счёт создан');
    }
}
