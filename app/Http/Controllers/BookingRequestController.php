<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingRequestController extends Controller
{
    private function currentSessionId(): string
    {
        // Laravel session id (работает для гостей тоже)
        return session()->getId();
    }

    public function index()
    {
        $sid = $this->currentSessionId();

        $bookings = Booking::query()
            ->with(['room.roomType'])
            ->when(auth()->check(), function ($q) use ($sid) {
                $q->where(function ($qq) use ($sid) {
                    $qq->where('user_id', auth()->id())
                       ->orWhere('session_id', $sid);
                });
            }, function ($q) use ($sid) {
                $q->where('session_id', $sid);
            })
            ->orderByDesc('id')
            ->get();

        return view('my-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::where('is_active', true)
            ->with('roomType')
            ->orderBy('number')
            ->get();

        return view('my-bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to'   => 'required|date|after:date_from',
            'note'      => 'nullable|string|max:1000',
        ]);

        $room = Room::findOrFail($request->room_id);

        $from = Carbon::parse($request->date_from);
        $to   = Carbon::parse($request->date_to);

        $nights = max(1, $from->diffInDays($to));
        $total  = $nights * (float) $room->price_per_night;

        Booking::create([
            'session_id' => $this->currentSessionId(),
            'user_id'    => auth()->id(), 
            'client_id'  => 1,         
            'room_id'    => $room->id,
            'date_from'  => $request->date_from,
            'date_to'    => $request->date_to,
            'status'     => 'pending',
            'total'      => $total,
            'note'       => $request->note,
        ]);

        return redirect()->route('my.bookings.index')
            ->with('success', 'Заявка успешно отправлена. Ожидайте подтверждения.');
    }
}