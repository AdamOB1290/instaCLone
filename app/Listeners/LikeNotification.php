<?php

namespace App\Listeners;

use App\Events\LikeEvent;
use App\Notifications\Like;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LikeNotification
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
     * @param  LikeEvent  $event
     * @return void
     */
    public function handle(LikeEvent $event)
    {
        if (isset($event->user->liked_comment)) {

            //create a variable for the user to be notified
            $commentUser = $event->user;

            //create the notification message
            $notificationMessage = ' has liked your comment!';

            // create index for the notifier id within post user object
            $commentUser['notifier_id'] = $event->user->liker_id;

            //index the notification message to the comment user
            $commentUser['notification_message'] = $notificationMessage;

            //create the dynamic index of the liked comment
            $commentUser['liked_object_index'] = 'liked_comment';

            //index the liked comment id in the comment user object
            $commentUser['liked_object'] = $commentUser->liked_comment;

            // send the notification to the comment user
            $commentUser->notify(new Like);

            // //remove the index created earlier
            // unset($commentUser['notificationMessage'], $commentUser['liker_id'], $commentUser['liked_comment']);



        } else if (isset($event->user->liked_post)) {

            //create a variable for the user to be notified
            $postUser = $event->user;

            //create the notification message
            $notificationMessage = ' has liked your post!';

            // create index for the notifier id within post user object
            $postUser['notifier_id'] = $event->user->liker_id;

            //index the notification message to the post user
            $postUser['notification_message'] = $notificationMessage;

            //createthe dynamic index of the liked post
            $postUser['liked_object_index'] = 'liked_post';

            //index the liked post id in the post user object
            $postUser['liked_object'] = $postUser->liked_post;

            // dd($postUser);
            // send the notification to the post user
            $postUser->notify(new Like);

            // //remove the index created earlier
            // unset($postUser['notificationMessage'], $postUser['liker_id'], $postUser['liked_post']);


        }
    }
}
