<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientShowRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function show(Client $client, ClientShowRequest $request)
    {
        $client = $client->with(['bookings' => function (HasMany $query) use ($request) {
            match ($request->get('filter')) {
                'past' => $query->where('start', '<=', now()),
                'future' => $query->where('start', '>=', now()),
                default => $query,
            };
        }])
            ->first();

        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request)
    {
        $client = new Client;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->city = $request->get('city');
        $client->postcode = $request->get('postcode');
        $client->save();

        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['message' => sprintf('Client "%s" successfully deleted', $client->name)]);
    }
}
