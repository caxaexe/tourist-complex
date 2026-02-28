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
        $amenity = Amenity::create($request->validated());

        logAudit('created', $amenity, null, $amenity->toArray());

        return redirect()->route($this->routePrefix().'amenities.index')
            ->with('success', 'Удобство добавлено');
    }

    public function edit(Amenity $amenity)
    {
        return view('amenities.edit', compact('amenity'));
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $old = $amenity->toArray();

        $amenity->update($request->validated());

        logAudit('updated', $amenity, $old, $amenity->toArray());

        return redirect()->route($this->routePrefix().'amenities.index')
            ->with('success', 'Удобство обновлено');
    }

    public function destroy(Amenity $amenity)
    {
        $old = $amenity->toArray();

        $amenity->delete();

        logAudit('deleted', $amenity, $old, null);

        return redirect()->route($this->routePrefix().'amenities.index')
            ->with('success', 'Удобство удалено');
    }
}
