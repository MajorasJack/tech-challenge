<?php

namespace Tests\Feature\Seeders;

use App\Booking;
use App\Client;
use App\Journal;
use Tests\TestCase;

class JournalSeeder extends TestCase
{
    public function testItCanRunTheClientSeederAsExpected()
    {
        $this->assertDatabaseCount(Journal::class, 0);

        factory(Client::class)->create();

        $this->artisan('db:seed', ['class' => 'JournalSeeder']);

        $this->assertDatabaseCount(Journal::class, 10);
    }
}
