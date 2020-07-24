<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\User;
use App\Post;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        // 'parent_comment_id' => Comment::all()->random()->id,
        'user_id' => User::all()->random()->id,
        'post_id' => Post::all()->random()->id,
        'content' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'likes' => $faker->numberBetween($min = 1, $max = ((User::count()) / 100) * 10),
    ];
});
