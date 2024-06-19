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

        $this->artisan('db:seed');

        $this->assertDatabaseCount(Journal::class, 1500);
    }
}
