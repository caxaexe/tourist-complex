<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Room;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmed;
use App\Mail\BookingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $payment = $request->query('payment');
        $query = Booking::query()
            ->with(['client', 'room.roomType', 'invoice'])
            ->withSum('payments', 'amount');

        if ($payment === 'unpaid') $query->havingRaw('COALESCE(payments_sum_amount, 0) <= 0');
        elseif ($payment === 'partial') $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) > 0 AND COALESCE(payments_sum_amount, 0) < total');
        elseif ($payment === 'paid') $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) >= total');

        $bookings = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        
        $activeCount = Booking::whereNotIn('status', ['cancelled', 'checked_out'])->count();
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $sumTotal = Booking::sum('total');

        return view('bookings.index', compact('bookings', 'payment', 'activeCount', 'confirmedCount', 'sumTotal'));
    }

    public function create()
    {
        $clients = Client::orderBy('full_name')->get();
        $rooms = Room::with('roomType')->where('is_active', true)->orderBy('number')->get();
        return view('bookings.create', compact('clients', 'rooms'));
    }

    public function store(StoreBookingRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            $hasConflict = Booking::query()
                ->where('room_id', $data['room_id'])
                ->where('status', '!=', 'cancelled')
                ->where('date_from', '<', $data['date_to'])
                ->where('date_to', '>', $data['date_from'])
                ->exists();

            if ($hasConflict) return back()->withInput()->withErrors(['date_from' => 'Номер занят']);

            $room = Room::findOrFail($data['room_id']);
            $nights = max(1, Carbon::parse($data['date_from'])->diffInDays(Carbon::parse($data['date_to'])));
            $data['total'] = $nights * (float)$room->price_per_night;
            $data['status'] = $data['status'] ?? 'pending';

            $booking = Booking::create($data);
            if ($booking->status === 'confirmed') $this->ensureInvoiceForBooking($booking);

            return redirect()->route($this->routePrefix() . 'bookings.index')->with('success', 'Создано');
        });
    }

    public function edit(Booking $booking)
    {
        $booking->load(['client', 'room.roomType', 'services', 'invoice.payments', 'payments']);
        $clients = Client::orderBy('full_name')->get();
        $rooms = Room::with('roomType')->where('is_active', true)->orderBy('number')->get();
        $services = Service::orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'clients', 'rooms', 'services'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        return DB::transaction(function () use ($request, $booking) {
            $data = $request->validated();
            $room = Room::findOrFail($data['room_id']);
            $nights = max(1, Carbon::parse($data['date_from'])->diffInDays(Carbon::parse($data['date_to'])));
            $data['total'] = $nights * (float)$room->price_per_night;

            $booking->update($data);
            if ($booking->status === 'confirmed') $this->ensureInvoiceForBooking($booking);

            return redirect()->route($this->routePrefix() . 'bookings.index')->with('success', 'Обновлено');
        });
    }

    public function destroy(Booking $booking)
    {
        $booking->invoice?->delete();
        $booking->delete();
        return redirect()->route($this->routePrefix() . 'bookings.index')->with('success', 'Удалено');
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        $this->ensureInvoiceForBooking($booking);
        return back()->with('success', 'Подтверждено');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Отменено');
    }

    public function checkIn(Booking $booking) { $booking->update(['status' => 'checked_in']); return back(); }
    public function checkOut(Booking $booking) { $booking->update(['status' => 'checked_out']); return back(); }

    private function ensureInvoiceForBooking(Booking $booking) { /* Ваша логика создания счета */ }
    private function routePrefix() { return auth()->user()->hasRole('admin') ? 'admin.' : 'staff.'; }
}