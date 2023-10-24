<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        return Client::all();
    }

    public function store(ClientRequest $request)
    {
        $this->authorize('create', Client::class);

        return Client::create($request->validated());
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        return $client;
    }

    public function update(ClientRequest $request, Client $client)
    {
        $this->authorize('update', $client);

        $client->update($request->validated());

        return $client;
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        return response()->json();
    }
}
