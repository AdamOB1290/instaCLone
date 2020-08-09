<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Notifications\Follow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FollowNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserFollowed  $event
     * @return void
     */
    public function handle(UserFollowed $event)
    {
        
        $followedUser = $event->user;

        $followerId = $event->user->followerId;

        //get the username of the user who followed
        $followerUsername = \App\User::findOrFail($followerId)->username;

        //create the notification message 
        $notificationMessage = 'You have been followed by ' . $followerUsername;

        //create index for the notification message within followed user object
        $followedUser['notificationMessage'] = $notificationMessage;

        $followedUser->notify(new Follow);
    }
}
