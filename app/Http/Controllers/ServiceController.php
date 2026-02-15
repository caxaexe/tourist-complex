<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->paginate(10);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(StoreServiceRequest $request)
    {
        Service::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Услуга добавлена');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Услуга обновлена');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Услуга удалена');
    }
}
