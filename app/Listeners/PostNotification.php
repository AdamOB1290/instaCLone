<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Notifications\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostNotification
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
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
       

        //fetch post user
        $user = \App\User::findOrFail($event->post->user_id);

        // check if it's a post or a story
        if ($event->post->type == 'post' || $event->post->type == 'story/post') {

            // loop through the ids of the users to notify
            foreach ($user->notification_preferences['posts'] as $key => $value) {
                //fetch the user to notify
                $userToNotify = \App\User::findOrFail($value);

                //create the notification message
                $notificationMessage = ' published a new post!';

                // create index for the notifier id within post user object
                $userToNotify['notifier_id'] = $event->post->user_id;

                // index the notification message
                $userToNotify['notification_message'] = $notificationMessage;

                // index the post id
                $userToNotify['post_id'] = (string)$event->post->id;

                $userToNotify->notify(new Post);

                
            }
           
            
        } elseif ($event->post->type == 'story' || $event->post->type == 'post/story') {

            // loop through the ids of the users to notify
            foreach ($user->notification_preferences['stories'] as $key => $value) {
                //fetch the user to notify
                $userToNotify = \App\User::findOrFail($value);

                //create the notification message
                $notificationMessage = ' published a new story!';

                // index the notification message
                $userToNotify['notification_message'] = $notificationMessage;

                // index the post id
                $userToNotify['post_id'] = (string)$event->post->id;

                $userToNotify->notify(new Post);
            }
           
            
        } 
        
    }
}
