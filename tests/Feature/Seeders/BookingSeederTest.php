<?php

namespace Tests\Feature\Seeders;

use App\Booking;
use App\Client;
use Tests\TestCase;

class BookingSeederTest extends TestCase
{
    public function testItCanRunTheClientSeederAsExpected()
    {
        $this->assertDatabaseCount(Booking::class, 0);

        $this->artisan('db:seed');

        $this->assertDatabaseCount(Booking::class, 1500);
    }
}
