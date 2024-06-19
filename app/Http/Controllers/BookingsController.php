<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Client;
use App\Http\Requests\ClientShowRequest;
use Illuminate\Http\JsonResponse;

class BookingsController extends Controller
{
    public function __invoke(Client $client, ClientShowRequest $request): JsonResponse
    {
        $bookingsQuery = Booking::where('client_id', $client->id);

        $bookings = match ($request->get('filter')) {
            'past' => $bookingsQuery->where('start', '<', now()),
            'future' => $bookingsQuery->where('start', '>', now()),
            default => $bookingsQuery,
        };

        return response()->json($bookings->get());
    }
}
