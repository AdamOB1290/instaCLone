<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\User;
use App\Post;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    
    return [
        // 'parent_comment_id' => $faker->randomElement([0, 0, 0, rand(1,120)]),
        'user_id' => User::all()->random()->id,
        'post_id' => Post::all()->random()->id,
        'content' => $faker->realText($maxNbChars = 200, $indexSize = 2),
    ];
});
