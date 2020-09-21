<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Notifications\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentNotification
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
     * @param  CommentCreated  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {

        //once the comment is created, get it's post id
        $postId = $event->comment->post_id;

        //fetch the post where the comment was created
        $post = \App\Post::findOrFail($postId);

        //fetch the user of the post where the comment was created
        $postUser = \App\User::findOrFail($post->user_id);

        //create the notification message 
        $notificationMessage = ' has commented on your post!';

        // create index for the notifier id within post user object
        $postUser['notifier_id'] = $event->comment->user_id;

        //create index for the notification message within post user object
        $postUser['notificationMessage'] = $notificationMessage;

        //create index for the comment id within post user object
        $postUser['commentId']  = $event->comment->id;

        // send the notification to the post user
        $postUser['real_time_notification']= $event->comment['real_time_notification'];
        $postUser->notify(new Comment);

        //remove the indexes created earlier
        unset($postUser['notificationMessage'], $postUser['commentId']);

       
        
    }
}
