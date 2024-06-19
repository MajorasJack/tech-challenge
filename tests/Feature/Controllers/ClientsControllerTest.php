<?php

namespace Tests\Feature\Controllers;

use App\Booking;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    use WithFaker;

    public function testThatItWillLoadTheClientBookingsAsExpected()
    {
        $client = factory(Client::class)->create();

        $booking = factory(Booking::class)->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($booking->id)
            ->assertSee($booking->notes)
            ->assertSee($booking->client_id);
    }

    public function testItWillDisplayTheClientBookingDateInTheCorrectFormat()
    {
        $client = factory(Client::class)->create();

        $booking = factory(Booking::class)->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($booking->start->format('l d F o, G:i'))
            ->assertSee($booking->end->format('l d F o, G:i'));
    }

    public function testItWillThrowAnErrorWhenProvidingInvalidCreationData()
    {
        $this->actingAs(factory(User::class)->create())
            ->post(route('clients.store'), [
                'name' => $this->faker->paragraph(10),
                'email' => $this->faker->sentence(),
                'phone' => $this->faker->sentence(),
            ])
                ->assertRedirect()
                ->assertSessionHasErrors([
                    'name' => 'The name may not be greater than 190 characters.',
                    'email' => 'The email must be a valid email address.',
                    'phone' => 'The phone format is invalid.',
                ]);
    }

    public function testItWillSubmitTheFormAsExpectedWhenThereIsNoErrors()
    {
        $this->actingAs(factory(User::class)->create())
            ->post(route('clients.store'), [
                'name' => $this->faker->word(),
                'email' => $this->faker->safeEmail(),
                'phone' => sprintf('+%s', $this->faker->randomNumber()),
            ])
            ->assertValid()
            ->assertSessionHasNoErrors();
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
            ->get(route('clients.show', $client))
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
            ->get(route('clients.show', $client))
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
            ->get(route('clients.show', ['client' => $client, 'filter' => 'past']))
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
            ->get(route('clients.show', ['client' => $client, 'filter' => 'future']))
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

    public function testThatItCanDeleteAClientAsExpected()
    {
        $client = factory(Client::class)->create();

        $this->actingAs(factory(User::class)->create())
            ->delete(route('clients.destroy', $client))
            ->assertOk()
            ->assertJson(['message' => sprintf('Client "%s" successfully deleted', $client->name)]);

        $this->assertDatabaseCount(Client::class, 0);
    }
}
