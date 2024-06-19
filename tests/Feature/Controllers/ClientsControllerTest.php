<?php

namespace Tests\Feature\Controllers;

use App\Client;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    use WithFaker;

    public function testItWillOnlyReturnAUsersClients()
    {
        $user = factory(User::class)->create();

        $client = factory(Client::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($client->name);
    }

    public function testAUserCantViewAnotherClient()
    {
        $user = factory(User::class)->create();

        $client = factory(Client::class)->create();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertForbidden();
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
            ->assertCreated()
            ->assertSessionHasNoErrors();
    }

    public function testThatItCanDeleteAClientAsExpected()
    {
        $client = factory(Client::class)->create();

        $this->actingAs(factory(User::class)->create())
            ->delete(route('clients.destroy', $client))
            ->assertNoContent();

        $this->assertDatabaseCount(Client::class, 0);
    }
}
