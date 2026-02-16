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
        $service = Service::create($request->validated());

        logAudit('created', $service, null, $service->toArray());

        return redirect()->route('services.index')
            ->with('success', 'Услуга добавлена');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $old = $service->toArray();

        $service->update($request->validated());

        logAudit('updated', $service, $old, $service->toArray());

        return redirect()->route('services.index')
            ->with('success', 'Услуга обновлена');
    }

    public function destroy(Service $service)
    {
        $old = $service->toArray();

        $service->delete();

        logAudit('deleted', $service, $old, null);

        return redirect()->route('services.index')
            ->with('success', 'Услуга удалена');
    }
}
