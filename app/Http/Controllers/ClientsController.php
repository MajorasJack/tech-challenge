<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ClientsController extends Controller
{
    public function index(): View
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function show(Client $client): View
    {
        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request)
    {
        return Client::create($request->validated());
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json(['message' => sprintf('Client "%s" successfully deleted', $client->name)]);
    }
}
