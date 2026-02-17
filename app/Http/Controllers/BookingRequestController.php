<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingRequestController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with('room.roomType')
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

        $nights = $from->diffInDays($to);
        $total  = $nights * (float)$room->price_per_night;

        Booking::create([
            'user_id'   => auth()->id(),
            'client_id' => 1, 
            'room_id'   => $room->id,
            'date_from' => $request->date_from,
            'date_to'   => $request->date_to,
            'status'    => 'pending',
            'total'     => $total,
            'note'      => $request->note,
        ]);

        return redirect()->route('my.bookings.index')
            ->with('success', 'Заявка успешно отправлена. Ожидайте подтверждения.');
    }
}
