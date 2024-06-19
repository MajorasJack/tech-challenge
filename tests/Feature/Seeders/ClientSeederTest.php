<?php

namespace Tests\Feature\Seeders;

use App\Client;
use Tests\TestCase;

class ClientSeederTest extends TestCase
{
    public function testItCanRunTheClientSeederAsExpected()
    {
        $this->assertDatabaseCount(Client::class, 0);

        $this->artisan('db:seed', ['class' => 'ClientSeeder']);

        $this->assertDatabaseCount(Client::class, 150);
    }
}
