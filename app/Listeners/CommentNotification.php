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
        $postUser->notify(new Comment);

        //remove the indexes created earlier
        unset($postUser['notificationMessage'], $postUser['commentId']);

       
        //if the comment has a parent
        // we need to use != because we want to ignore the datatype
        if ($event->comment->parent_comment_id != 0) {
           

            //fetch the parent comment
            $parentComment = \App\Comment::findOrFail($event->comment->parent_comment_id);

            //fetch the user of the parent comment
            $parentCommentUser = \App\User::findOrFail($parentComment->user_id);

            //get the username of the user who created the comment
            $commentUsername = \App\User::findOrFail($event->comment->user_id)->username;

            //create the notification message 
            $notificationMessage = $commentUsername . ' has replied to your comment!';

            //create index for the notification message within post user object
            $parentCommentUser['notificationMessage'] = $notificationMessage;

            // send the notification to the post user
            $parentCommentUser->notify(new Comment);

            //remove the index created earlier
            // unset($parentCommentUser['commentUserId']);

            
        }
        
    }
}
