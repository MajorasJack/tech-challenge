<?php

/** @var Factory $factory */

use App\Client;
use App\Journal;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Journal::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
        'text' => $faker->paragraph(),
        'client_id' => factory(Client::class)->create()->id,
        'user_id' => factory(User::class)->create()->id,
    ];
});
