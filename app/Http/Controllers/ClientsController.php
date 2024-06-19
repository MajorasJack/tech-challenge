<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientShowRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;


class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show(Client $client)
    {
        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request)
    {
        return Client::create($request->validated());
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['message' => sprintf('Client "%s" successfully deleted', $client->name)]);
    }
}
