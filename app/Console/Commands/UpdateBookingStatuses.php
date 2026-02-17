<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Invoice;

class UpdateBookingStatuses extends Command
{
    protected $signature = 'bookings:update-statuses';
    protected $description = 'Автоматически обновляет статусы бронирований по датам';

    public function handle()
{
    $today = now()->toDateString();

    // confirmed -> checked_in
    $bookingsToCheckIn = \App\Models\Booking::where('status', 'confirmed')
        ->whereDate('date_from', '<=', $today)
        ->get();

    foreach ($bookingsToCheckIn as $b) {
        $old = $b->toArray();
        $b->update(['status' => 'checked_in']);
        logAudit('updated', $b, $old, $b->fresh()->toArray());
    }

    // checked_in -> checked_out
    $bookingsToCheckOut = \App\Models\Booking::where('status', 'checked_in')
        ->whereDate('date_to', '<=', $today)
        ->get();

    foreach ($bookingsToCheckOut as $b) {
        $old = $b->toArray();
        $b->update(['status' => 'checked_out']);
        logAudit('updated', $b, $old, $b->fresh()->toArray());

        // ✅ НОВОЕ: закрываем счет если paid
        $invoice = Invoice::where('booking_id', $b->id)->first();
        if ($invoice && $invoice->status === 'paid' && $invoice->status !== 'closed') {
            $iOld = $invoice->toArray();
            $invoice->update(['status' => 'closed']);
            logAudit('updated', $invoice, $iOld, $invoice->fresh()->toArray());
        }
    }

    $this->info("Auto statuses done");

    return 0;
}
}
