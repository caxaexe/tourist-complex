<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;

class BookingInvoiceController extends Controller
{

    public function store(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->withErrors(['invoice' => 'Нельзя создать счёт для отменённого бронирования.']);
        }

        $booking->load('invoice');

        if ($booking->invoice) {
            return redirect()->route($this->routePrefix().'invoices.show', $booking->invoice);
        }

        app(BookingController::class)->ensureInvoiceForBooking($booking);

        $booking->refresh()->load('invoice');

       $prefix = auth()->user()?->hasRole('admin') ? 'admin.' : 'staff.';

        return redirect()->route($prefix.'invoices.show', $booking->invoice)
            ->with('success', 'Счёт создан');
        }
}