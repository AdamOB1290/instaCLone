<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Post;
use App\User;
use Faker\Generator as Faker;
$factory->define(Post::class, function (Faker $faker) {
    Unsplash\HttpClient::init([
        'applicationId'	=> '****REMOVED****',
        'secret'	=> '****REMOVED****',
        'callbackUrl'	=> 'https://your-application.com/oauth/callback',
        'utmSource' => 'NAME OF YOUR APPLICATION'
    ]);
    $search = 'landscape';
    $page = 1;
    $per_page = 30;
    $order_by = 'popular';
    $orientation = 'landscape';
    $imagePostArray = [];
    for ($page=1; $page < 3; $page++) { 
    $photos = Unsplash\Search::photos($search, $page, $per_page, $orientation)->getResults();
    foreach ($photos as $key => $photo) {
        array_push($imagePostArray, $photo["urls"]["full"]);
    }
    }
    $mediaFile = $faker->randomElement($imagePostArray);
    return [
        'user_id' => User::all()->random()->id,
        // 'cover' => $faker->image('storage/app/public/uploads',640,480, null, false),
        'media_file' => $mediaFile,
        // 'media_file' => $faker->imageUrl( 640, 480, null, true, null, false),
        'media_type' => 'image',
        // 'media_file' =>$faker->image('storage/app/public/uploads', 640, 480, null, false),
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'type' => $faker->randomElement(['story', 'post']),
    ];
});