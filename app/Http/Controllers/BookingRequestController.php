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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        ]);

        $request->session()->put('guest_client_id', $client->id);
        return $client->id;
    }

    public function index(Request $request)
    {
        $this->forbidStaffAdmin();
        $clientId = $this->getOrCreateGuestClientId($request);

        $bookings = Booking::where('client_id', $clientId)
            ->with('room.roomType')
            ->orderByDesc('id')
            ->get();

        return view('my-bookings.index', compact('bookings'));
    }

    public function create(Request $request)
{
    $this->forbidStaffAdmin();
    $this->getOrCreateGuestClientId($request);

    $rooms = Room::where('is_active', true)->with('roomType')->orderBy('number')->get();

    $disabledByRoom = Booking::whereIn('status', ['confirmed', 'checked_in'])
        ->get(['room_id', 'date_from', 'date_to'])
        ->groupBy('room_id')
        ->map(function ($bookings) {
            return $bookings->map(fn($b) => [
                'from' => Carbon::parse($b->date_from)->format('Y-m-d'),
                'to'   => Carbon::parse($b->date_to)->format('Y-m-d')
            ]);
        });

    return view('my-bookings.create', compact('rooms', 'disabledByRoom'));
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
            'note'      => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated, $clientId, $request) {
            // ОДНА правильная проверка на пересечение
            $hasOverlap = Booking::where('room_id', $validated['room_id'])
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->where('date_from', '<', $validated['date_to'])
                ->where('date_to', '>', $validated['date_from'])
                ->exists();

            if ($hasOverlap) {
                throw ValidationException::withMessages([
                    'date_from' => 'Этот номер уже занят на выбранные даты. Пожалуйста, выберите другие.'
                ]);
            }

            $client = Client::findOrFail($clientId);
            $client->update([
                'full_name' => $validated['full_name'],
                'phone'     => $validated['phone'],
                'email'     => $validated['email'],
            ]);

            $room = Room::findOrFail($validated['room_id']);
            $from = Carbon::parse($validated['date_from']);
            $to   = Carbon::parse($validated['date_to']);
            $nights = max(1, $from->diffInDays($to));
            
            $booking = Booking::create([
                'client_id' => $clientId,
                'room_id'   => $room->id,
                'date_from' => $validated['date_from'],
                'date_to'   => $validated['date_to'],
                'status'    => 'pending',
                'total'     => $nights * (float)$room->price_per_night,
                'note'      => $validated['note'] ?? null,
            ]);

            Mail::to('caxa5578@gmail.com')->send(new NewBookingRequest($booking->load('room')));

            return redirect()->route('my.bookings.index')->with('success', 'Заявка принята!');
        });
    }
}