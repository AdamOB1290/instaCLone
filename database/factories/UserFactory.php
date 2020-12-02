<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(User::class, function (Faker $faker) {
    Unsplash\HttpClient::init([
        'applicationId'	=> '****REMOVED****',
        'secret'	=> '****REMOVED****',
        'callbackUrl'	=> 'https://your-application.com/oauth/callback',
        'utmSource' => 'NAME OF YOUR APPLICATION'
    ]);
    $search = 'person';
    $page = 1;
    $per_page = 30;
    $order_by = 'popular';
    $orientation = 'squarish';
    $pfpArray = [];
    for ($page=1; $page < 3; $page++) { 
    $photos = Unsplash\Search::photos($search, $page, $per_page, $orientation)->getResults();
    foreach ($photos as $key => $photo) {
        array_push($pfpArray, $photo["urls"]["regular"]);
    }
    }
    $pfp = $faker->randomElement($pfpArray);
    $Ugender = $faker->randomElement(['Male', 'Female']);
    return [
        'name' => $faker->name($gender = $Ugender),
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'phone_number' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'username' => str_replace(" ", "", $faker->text($maxNbChars = 12)),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'bio' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'gender' => $Ugender,
        'pfp' => $pfp,
        // 'pfp' => $faker->imageUrl(640, 480, null, true, null, false),
        'remember_token' => Str::random(10),
    ];
});