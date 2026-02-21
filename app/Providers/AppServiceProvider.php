<?php

namespace App\Providers;
use App\Models\Booking;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['bookings.*'], function ($view) {

            $today = now()->toDateString();

            $activeCount = Booking::whereNotIn('status', ['cancelled', 'checked_out'])->count();

            $checkInToday = Booking::whereDate('date_from', $today)
                ->where('status', '!=', 'cancelled')
                ->count();

            $checkOutToday = Booking::whereDate('date_to', $today)
                ->where('status', '!=', 'cancelled')
                ->count();

            $confirmedCount = Booking::where('status', 'confirmed')->count();

            $sumTotal = Booking::sum('total');

            $view->with(compact(
                'activeCount',
                'checkInToday',
                'checkOutToday',
                'confirmedCount',
                'sumTotal'
            ));
        });
    }
}
