<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\JournalStoreRequest;
use App\Journal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class JournalsController extends Controller
{
    public function index(Client $client): JsonResponse
    {
        return response()->json($client->journals);
    }

    public function create(Client $client): View
    {
        return view('journals.create', ['client' => $client]);
    }

    public function store(Client $client, JournalStoreRequest $request): JsonResponse
    {
        Journal::create([
            'date' => $request->get('date'),
            'text' => $request->get('text'),
            'client_id' => $client->id,
            'user_id' => auth()->user()->getAuthIdentifier(),
        ]);

        return response()->json(['url' => route('clients.show', $client)], Response::HTTP_CREATED);
    }

    public function destroy(Client $client, Journal $journal): Response
    {
        $journal->delete();

        return response()->noContent();
    }
}
