<?php

namespace Tests\Feature\Controllers;

use App\Booking;
use App\Client;
use App\User;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    public function testThatItWillLoadTheClientBookingsAsExpected()
    {
        $client = factory(Client::class)->create();

        $booking = factory(Booking::class)->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($booking->id)
            ->assertSee($booking->notes)
            ->assertSee($booking->client_id)
            ->assertSee($booking->start->format('l d F o, G:i'))
            ->assertSee($booking->end->format('l d F o, G:i'));
    }
}
