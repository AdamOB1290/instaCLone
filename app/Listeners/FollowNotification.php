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

        //create the notification message 
        $notificationMessage = ' has followed you.';

        // create index for the notifier id within post user object
        $followedUser['notifier_id'] = $event->user->followerId;

        //create index for the notification message within followed user object
        $followedUser['notificationMessage'] = $notificationMessage;

        $followedUser->notify(new Follow);
    }
}
