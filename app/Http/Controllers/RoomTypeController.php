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
        RoomType::create($request->validated());

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
        $room_type->update($request->validated());

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Тип номера обновлён');
    }

    public function destroy(RoomType $room_type)
    {
        $room_type->delete();

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Тип номера удалён');
    }
}
