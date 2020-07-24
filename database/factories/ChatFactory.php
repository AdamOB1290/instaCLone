<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Chat;
use App\User;
use Faker\Generator as Faker;

$factory->define(Chat::class, function (Faker $faker) {
    return [
        'sender_id' => User::all()->random()->id,
        'receiver_id' => User::all()->random()->id,
        'content' => $faker->realText($maxNbChars = 200, $indexSize = 2),
    ];
});
