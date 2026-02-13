<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::orderBy('id', 'desc')->paginate(10);
        return view('amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('amenities.create');
    }

    public function store(StoreAmenityRequest $request)
    {
        Amenity::create($request->validated());

        return redirect()->route('amenities.index')
            ->with('success', 'Удобство добавлено');
    }

    public function edit(Amenity $amenity)
    {
        return view('amenities.edit', compact('amenity'));
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $amenity->update($request->validated());

        return redirect()->route('amenities.index')
            ->with('success', 'Удобство обновлено');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();

        return redirect()->route('amenities.index')
            ->with('success', 'Удобство удалено');
    }
}
