<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientsController extends Controller
{
    public function index(): View
    {
        return view('clients.index', ['clients' => Client::with('bookings')->get()]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function show(Client $client): View
    {
        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return response()->json($client, Response::HTTP_CREATED);
    }

    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }
}
