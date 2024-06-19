<?php

namespace Database\Seeders;

use App\Booking;
use App\Client;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Client::all()->each(function (Client $client) {
            factory(Booking::class, 10)->create([
                'client_id' => $client->id,
            ]);
        });
    }
}
