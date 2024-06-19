<?php

namespace Tests\Feature\Controllers;

use App\Booking;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingsControllerTest extends TestCase
{
    use WithFaker;

    public function testThatItWillLoadTheClientBookingsAsExpected()
    {
        $client = factory(Client::class)->create();

        $booking = factory(Booking::class)->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', $client))
            ->assertOk()
            ->assertJson([
                [
                    'id' => $booking->id,
                    'notes' => $booking->notes,
                    'client_id' => $booking->client_id,
                    'start' => $booking->start->format('l d F o, G:i'),
                    'end' => $booking->end->format('l d F o, G:i'),
                ]
            ]);
    }

    public function testItWillDisplayTheClientBookingDateInTheCorrectFormat()
    {
        $client = factory(Client::class)->create();

        $booking = factory(Booking::class)->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', $client))
            ->assertOk()
            ->assertJson([
                [
                    'start' => $booking->start->format('l d F o, G:i'),
                    'end' => $booking->end->format('l d F o, G:i'),
                ]
            ]);
    }

    public function testThatItWillDisplayTheClientBookingsInTheCorrectOrder()
    {
        $client = factory(Client::class)->create();

        $oldBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDays($this->faker->numberBetween(1, 100)),
        ]);

        $currentBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now(),
        ]);

        $futureBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->addDays($this->faker->numberBetween(1, 100)),
        ]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', $client))
            ->assertOk()
            ->assertSeeInOrder([
                $oldBooking->start->format('l d F o, G:i'),
                $oldBooking->end->format('l d F o, G:i'),
                $currentBooking->start->format('l d F o, G:i'),
                $currentBooking->end->format('l d F o, G:i'),
                $futureBooking->start->format('l d F o, G:i'),
                $futureBooking->end->format('l d F o, G:i'),
            ]);
    }

    public function testThatItWillDisplayAllBookingsWhenNothingIsFiltered()
    {
        $client = factory(Client::class)->create();

        $oldBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDays($this->faker->numberBetween(1, 100)),
        ]);

        $currentBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now(),
        ]);

        $futureBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->addDays($this->faker->numberBetween(1, 100)),
        ]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', $client))
            ->assertOk()
            ->assertSee([
                $futureBooking->start->format('l d F o, G:i'),
                $futureBooking->end->format('l d F o, G:i'),
                $oldBooking->start->format('l d F o, G:i'),
                $oldBooking->end->format('l d F o, G:i'),
                $currentBooking->start->format('l d F o, G:i'),
                $currentBooking->end->format('l d F o, G:i'),
            ]);
    }

    public function testThatItWillOnlyDisplayPastBookingsWhenFiltering()
    {
        $client = factory(Client::class)->create();

        $oldBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDays($this->faker->numberBetween(1, 100)),
        ]);

        $currentBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now(),
        ]);

        $futureBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->addDays($this->faker->numberBetween(1, 100)),
        ]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', ['client' => $client, 'filter' => 'past']))
            ->assertOk()
            ->assertSee([
                $oldBooking->start->format('l d F o, G:i'),
                $oldBooking->end->format('l d F o, G:i'),
            ])
            ->assertDontSee([
                $currentBooking->start->format('l d F o, G:i'),
                $currentBooking->end->format('l d F o, G:i'),
                $futureBooking->start->format('l d F o, G:i'),
                $futureBooking->end->format('l d F o, G:i'),
            ]);
    }

    public function testThatItWillOnlyDisplayFutureBookingsWhenFiltering()
    {
        $client = factory(Client::class)->create();

        $oldBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDays($this->faker->numberBetween(1, 100)),
        ]);

        $currentBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now(),
        ]);

        $futureBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->addDays($this->faker->numberBetween(1, 100)),
        ]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('client.bookings', ['client' => $client, 'filter' => 'future']))
            ->assertOk()
            ->assertSee([
                $futureBooking->start->format('l d F o, G:i'),
                $futureBooking->end->format('l d F o, G:i'),
            ])
            ->assertDontSee([
                $oldBooking->start->format('l d F o, G:i'),
                $oldBooking->end->format('l d F o, G:i'),
                $currentBooking->start->format('l d F o, G:i'),
                $currentBooking->end->format('l d F o, G:i'),
            ]);
    }
}
