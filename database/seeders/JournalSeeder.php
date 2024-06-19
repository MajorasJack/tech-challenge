<?php

namespace Database\Seeders;

use App\Client;
use App\Journal;
use App\User;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Client::all()->each(function (Client $client) {
            factory(Journal::class, 10)->create([
                'client_id' => $client->id,
                'user_id' => User::all()->first(),
            ]);
        });
    }
}
