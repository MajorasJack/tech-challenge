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
