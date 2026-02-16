<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::orderBy('id', 'desc')->paginate(10);

        return view('room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room_types.create');
    }

    public function store(StoreRoomTypeRequest $request)
    {
        $roomType = RoomType::create($request->validated());

        logAudit('created', $roomType, null, $roomType->toArray());

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Тип номера создан');
    }

    public function edit(RoomType $room_type)
    {
        return view('room_types.edit', compact('room_type'));
    }

    public function update(UpdateRoomTypeRequest $request, RoomType $room_type)
    {
        $old = $room_type->toArray();

        $room_type->update($request->validated());

        logAudit('updated', $room_type, $old, $room_type->toArray());

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Тип номера обновлён');
    }

    public function destroy(RoomType $room_type)
    {
        $old = $room_type->toArray();

        $room_type->delete();

        logAudit('deleted', $room_type, $old, null);

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Тип номера удалён');
    }
}
