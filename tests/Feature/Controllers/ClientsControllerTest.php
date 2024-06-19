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
}
