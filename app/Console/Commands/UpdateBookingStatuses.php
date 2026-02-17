<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class UpdateBookingStatuses extends Command
{
    protected $signature = 'bookings:update-statuses';
    protected $description = 'Автоматически обновляет статусы бронирований по датам';

    public function handle()
    {
        $today = now()->toDateString();

        // confirmed -> checked_in (если сегодня заезд)
        $checkedIn = Booking::where('status', 'confirmed')
            ->whereDate('date_from', '<=', $today)
            ->update(['status' => 'checked_in']);

        // checked_in -> checked_out (если сегодня/прошло выселение)
        $checkedOut = Booking::where('status', 'checked_in')
            ->whereDate('date_to', '<=', $today)
            ->update(['status' => 'checked_out']);

        $this->info("Checked in: {$checkedIn}, Checked out: {$checkedOut}");

        return 0;
    }
}
