<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingRequestController extends Controller
{
    private function forbidStaff()
    {
        $u = auth()->user();

        if ($u && $u->hasAnyRole(['admin', 'employee'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Клиентская зона недоступна для персонала.');
        }

        return null;
    }

    private function getOrCreateGuestClientId(Request $request): int
    {
        $clientId = (int) $request->session()->get('client_id', 0);
        if ($clientId > 0 && Client::whereKey($clientId)->exists()) {
            return $clientId;
        }

        $client = Client::create([
            'full_name' => 'Guest #' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)),
            'phone' => null,
            'email' => null,
            'passport_series' => null,
            'passport_number' => null,
            'birth_date' => null,
            'address' => null,
        ]);

        $request->session()->put('client_id', $client->id);

        return (int) $client->id;
    }

    public function index(Request $request)
    {
        if ($r = $this->forbidStaff()) return $r;

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
        if ($r = $this->forbidStaff()) return $r;

        // просто гарантируем что клиент есть
        $this->getOrCreateGuestClientId($request);

        $rooms = Room::where('is_active', true)
            ->with('roomType')
            ->orderBy('number')
            ->get();

        return view('my-bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        if ($r = $this->forbidStaff()) return $r;

        $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to'   => 'required|date|after:date_from',
            'note'      => 'nullable|string|max:1000',
        ]);

        $clientId = $this->getOrCreateGuestClientId($request);

        $room = Room::findOrFail($request->room_id);

        $from = Carbon::parse($request->date_from);
        $to   = Carbon::parse($request->date_to);

        $nights = max(1, $from->diffInDays($to));
        $total  = $nights * (float) $room->price_per_night;

        Booking::create([
            'user_id'   => auth()->id(), // null для гостя — нормально
            'client_id' => $clientId,
            'room_id'   => $room->id,
            'date_from' => $request->date_from,
            'date_to'   => $request->date_to,
            'status'    => 'pending',
            'total'     => $total,
            'note'      => $request->note,
        ]);

        return redirect()->route('client.my.bookings.index')
            ->with('success', 'Заявка успешно отправлена. Ожидайте подтверждения.');
    }
}