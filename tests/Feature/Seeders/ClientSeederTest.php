<?php

namespace Tests\Feature\Seeders;

use App\Client;
use Tests\TestCase;

class ClientSeederTest extends TestCase
{
    public function testItCanRunTheClientSeederAsExpected()
    {
        $this->assertDatabaseCount(Client::class, 0);

        $this->artisan('db:seed');

        $this->assertDatabaseCount(Client::class, 150);
    }
}
