<?php

namespace Tests\Unit;

use App\Client;
use App\User;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function testThatAClientCanBeAssociatedToAUser()
    {
        $user = factory(User::class)->create();

        $client = factory(Client::class)->create(['user_id' => null]);

        $client->user()->associate($user);

        $this->assertSame($user->id, $client->user_id);
    }
}
