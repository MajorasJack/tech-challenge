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
        return view('clients.index', ['clients' => auth()->user()->clients()->with('bookings')->get()]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function show(Client $client): View|JsonResponse
    {
        if ($client->user_id !== auth()->user()->getAuthIdentifier()) {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = auth()->user()->clients()->create($request->validated());

        return response()->json($client, Response::HTTP_CREATED);
    }

    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }
}
