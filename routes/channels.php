<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('likes.{id}', function () {
//     dd(Auth::check());
//     return Auth::check();
// });

Broadcast::channel('likes.{id}', function ($user, $id) {
    // dd((int) $user->id === (int) $id);
    
    return (int) $user->id === (int) $id;
});
