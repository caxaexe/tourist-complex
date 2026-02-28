<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $clients = Client::query()
            ->when($q, function ($query) use ($q) {
                $query->where('full_name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients', 'q'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());

        logAudit('created', $client, null, $client->toArray());

        return redirect()
            ->route($this->routePrefix().'clients.index')
            ->with('success', 'Клиент добавлен');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $old = $client->toArray();

        $client->update($request->validated());

        logAudit('updated', $client, $old, $client->toArray());

        return redirect()
            ->route($this->routePrefix().'clients.index')
            ->with('success', 'Данные клиента обновлены');
    }

    public function destroy(Client $client)
    {
        $old = $client->toArray();

        $client->delete();

        logAudit('deleted', $client, $old, null);

        return redirect()
            ->route($this->routePrefix().'clients.index')
            ->with('success', 'Клиент удалён');
    }
}
