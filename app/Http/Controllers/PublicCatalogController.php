<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function rooms()
    {
        // Используем is_active = true согласно вашей миграции комнат
        $rooms = Room::with(['roomType', 'amenities'])->where('is_active', true)->get();
        $roomTypes = RoomType::with('rooms')->get();

        return view('public.rooms', compact('rooms', 'roomTypes'));
    }

    public function services()
    {
        $services = Service::all();
        return view('public.services', compact('services'));
    }
}