<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Amenity;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $rooms = Room::query()
            ->with('roomType')
            ->when($q, function ($query) use ($q) {
                $query->where('number', 'like', "%{$q}%")
                      ->orWhere('title', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', compact('rooms', 'q'));
    }

    public function create()
    {
        $roomTypes = RoomType::orderBy('name')->get();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $room = Room::create($data);

        // ✅ AUDIT: created
        logAudit('created', $room, null, $room->toArray());

        return redirect()->route('rooms.index')
            ->with('success', 'Номер добавлен');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        $selectedAmenityIds = $room->amenities()->pluck('amenities.id')->toArray();

        return view('rooms.edit', compact('room', 'roomTypes', 'amenities', 'selectedAmenityIds'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $old = $room->toArray();

        $room->update($data);

        // ✅ AUDIT: updated
        logAudit('updated', $room, $old, $room->toArray());

        return redirect()->route('rooms.index')
            ->with('success', 'Номер обновлён');
    }

    public function destroy(Room $room)
    {
        $old = $room->toArray();

        $room->delete();

        // ✅ AUDIT: deleted
        logAudit('deleted', $room, $old, null);

        return redirect()->route('rooms.index')
            ->with('success', 'Номер удалён');
    }
}
