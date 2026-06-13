<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\Amenity;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    
    public function rooms()
    {
        // Загружаем комнаты вместе с их типом и привязанными удобствами
        $rooms = Room::with(['roomType', 'amenities'])->where('status', 'available')->get();
        
        // Также можно сгруппировать по типам комнат для красивого вывода
        $roomTypes = RoomType::with('rooms')->get();

        return view('public.rooms', compact('rooms', 'roomTypes'));
    }

    
    public function services()
    {
        $services = Service::all();
        
        return view('public.services', compact('services'));
    }
}