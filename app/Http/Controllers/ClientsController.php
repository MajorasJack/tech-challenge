<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;

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

    public function show($client)
    {
        $client = Client::where('id', $client)
            ->with('bookings')
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
