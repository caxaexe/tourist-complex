<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingRequestController extends Controller
{
    private function forbidStaffAdmin(): void
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff())) {
            abort(403, 'Админ/сотрудник не может подавать клиентские заявки.');
        }
    }

    private function getOrCreateGuestClientId(Request $request): int
    {
        $clientId = (int) $request->session()->get('guest_client_id', 0);

        if ($clientId > 0 && Client::whereKey($clientId)->exists()) {
            return $clientId;
        }

        $client = Client::create([
            'full_name' => 'Гость ' . Str::upper(Str::random(6)),
            'phone'     => null,
            'email'     => null,
        ]);

        $request->session()->put('guest_client_id', $client->id);

        return $client->id;
    }

    public function index(Request $request)
    {
        $this->forbidStaffAdmin();

        $clientId = $this->getOrCreateGuestClientId($request);

        $bookings = Booking::query()
            ->where('client_id', $clientId)
            ->with('room.roomType')
            ->orderByDesc('id')
            ->get();

        return view('my-bookings.index', compact('bookings'));
    }

    public function create(Request $request)
{
    $this->forbidStaffAdmin();
    
    $disabledRanges = Booking::whereIn('status', ['confirmed', 'checked_in'])
        ->get(['date_from', 'date_to'])
        ->map(fn($b) => ['from' => $b->date_from, 'to' => $b->date_to]);

    $rooms = Room::where('is_active', true)->get();

    return view('my-bookings.create', compact('rooms', 'disabledRanges'));
}

    public function store(Request $request)
{
    $this->forbidStaffAdmin();
    $clientId = $this->getOrCreateGuestClientId($request);

    $validated = $request->validate([
        'full_name' => 'required|string|min:3|max:255',
        'room_id'   => 'required|exists:rooms,id',
        'date_from' => 'required|date|after_or_equal:today',
        'date_to'   => 'required|date|after:date_from',
        'phone'     => 'required|string|min:5|max:30',
        'email'     => 'required|email:rfc,dns|max:255',
    ]);

    return \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $clientId) {
        $hasOverlap = Booking::where('room_id', $validated['room_id'])
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('date_from', '<', $validated['date_to'])
            ->where('date_to', '>', $validated['date_from'])
            ->exists();

        if ($hasOverlap) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'date_from' => 'Выбранный номер уже занят на эти даты.'
            ]);
        }

        $client = Client::findOrFail($clientId);
        $client->update($validated);

        $room = Room::findOrFail($validated['room_id']);
        $nights = Carbon::parse($validated['date_from'])->diffInDays(Carbon::parse($validated['date_to']));
        
        $booking = Booking::create([
            'client_id' => $clientId,
            'room_id'   => $room->id,
            'date_from' => $validated['date_from'],
            'date_to'   => $validated['date_to'],
            'status'    => 'pending',
            'total'     => $nights * (float)$room->price_per_night,
            'note'      => $request->note,
        ]);

        Mail::to('caxa5578@gmail.com')->send(new NewBookingRequest($booking));

        return redirect()->route('my.bookings.index')->with('success', 'Заявка принята!');
    });
}
}