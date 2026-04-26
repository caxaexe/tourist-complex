<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Client;
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

        // гарантируем что клиент в сессии уже есть
        $this->getOrCreateGuestClientId($request);

        $rooms = Room::where('is_active', true)
            ->with('roomType')
            ->orderBy('number')
            ->get();

        return view('my-bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $this->forbidStaffAdmin();

        $clientId = $this->getOrCreateGuestClientId($request);

        $validated = $request->validate([
            // Добавили обязательное ФИО
            'full_name' => 'required|string|min:3|max:255',
            'room_id'   => 'required|exists:rooms,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to'   => 'required|date|after:date_from',
            'note'      => 'nullable|string|max:1000',

            // обязательные контакты
            'phone'     => 'required|string|min:5|max:30',
            'email'     => 'required|email:rfc,dns|max:255',
        ], [
            'full_name.required' => 'Пожалуйста, укажите ваше ФИО.',
            'full_name.min'      => 'ФИО должно содержать минимум 3 символа.',
        ]);

        // сохраняем контакты и ФИО гостя в клиенте (важно!)
        $client = \App\Models\Client::findOrFail($clientId);
        $client->update([
            'full_name' => $validated['full_name'],
            'phone'     => $validated['phone'],
            'email'     => $validated['email'],
        ]);

        $room = \App\Models\Room::findOrFail($validated['room_id']);

        $from = \Carbon\Carbon::parse($validated['date_from']);
        $to   = \Carbon\Carbon::parse($validated['date_to']);

        $nights = $from->diffInDays($to);
        $total  = $nights * (float) $room->price_per_night;

        \App\Models\Booking::create([
            'user_id'   => null,        // гость
            'client_id' => $clientId,   // из сессии
            'room_id'   => $room->id,
            'date_from' => $validated['date_from'],
            'date_to'   => $validated['date_to'],
            'status'    => 'pending',
            'total'     => $total,
            'note'      => $validated['note'] ?? null,
        ]);

        return redirect()->route('my.bookings.index')
            ->with('success', 'Заявка успешно отправлена. Ожидайте подтверждения.');
    }
}