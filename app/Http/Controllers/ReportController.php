<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : now()->endOfDay();

        $paymentsTotal = Payment::whereBetween('paid_at', [$from, $to])->sum('amount');
        $paymentsCount = Payment::whereBetween('paid_at', [$from, $to])->count();

        $invoicesTotal = Invoice::whereBetween('issued_at', [$from->toDateString(), $to->toDateString()])->sum('total');
        $invoicesByStatus = Invoice::select('status', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total) as sum_total'))
            ->whereBetween('issued_at', [$from->toDateString(), $to->toDateString()])
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $topServices = DB::table('booking_service')
            ->join('services', 'services.id', '=', 'booking_service.service_id')
            ->join('bookings', 'bookings.id', '=', 'booking_service.booking_id')
            ->whereBetween('bookings.date_from', [$from->toDateString(), $to->toDateString()])
            ->select(
                'services.id',
                'services.name',
                DB::raw('SUM(booking_service.quantity) as qty'),
                DB::raw('SUM(booking_service.quantity * booking_service.price_snapshot) as revenue')
            )
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $roomOccupancy = Booking::query()
            ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->where('bookings.status', '!=', 'cancelled')
            ->where('bookings.date_from', '<', $to->toDateString())
            ->where('bookings.date_to', '>', $from->toDateString())
            ->select(
                'rooms.id',
                'rooms.number',
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw('SUM(DATEDIFF(bookings.date_to, bookings.date_from)) as nights')
            )
            ->groupBy('rooms.id', 'rooms.number')
            ->orderByDesc('nights')
            ->limit(15)
            ->get();

        $chartData = Payment::select(DB::raw('DATE(paid_at) as date'), DB::raw('sum(amount) as total'))
            ->where('paid_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('reports.index', compact(
            'from', 'to', 'paymentsTotal', 'paymentsCount', 'invoicesTotal',
            'invoicesByStatus', 'topServices', 'roomOccupancy', 'chartData'
        ));
    }
}