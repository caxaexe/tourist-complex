<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBookingStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update booking statuses (check-in/check-out)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // check-in
        $in = \App\Models\Booking::where('status', 'confirmed')
            ->whereDate('date_from', $today)
            ->get();

        foreach ($in as $b) {
            $old = $b->toArray();
            $b->update(['status' => 'checked_in']);
            logAudit('updated', $b, $old, $b->toArray());
        }

        // check-out
        $out = \App\Models\Booking::whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('date_to', '<=', $today)
            ->get();

        foreach ($out as $b) {
            $old = $b->toArray();
            $b->update(['status' => 'checked_out']);
            logAudit('updated', $b, $old, $b->toArray());
        }

        $this->info('Booking statuses updated.');
    }

}
