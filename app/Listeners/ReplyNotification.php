<?php

namespace App\Listeners;

use App\Events\ReplyCreated;
use App\Notifications\Reply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReplyNotification
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
     * @param  ReplyCreated  $event
     * @return void
     */
    public function handle(ReplyCreated $event)
    {
          //fetch the parent comment
          $parentComment = \App\Comment::findOrFail($event->reply->parent_comment_id);

          //fetch the user of the parent comment
          $parentCommentUser = \App\User::findOrFail($parentComment->user_id);

          // create index for the notifier id within comment user object
          $parentCommentUser['notifier_id'] = $event->reply->user_id;
          
          
          // create index for the notifier id within comment user object
          $parentCommentUser['comment_id'] = $event->reply->id;
          
          
          //create the notification message 
          $notificationMessage =' has replied to your comment!';
          
          //create index for the notification message within post user object
          $parentCommentUser['notificationMessage'] = $notificationMessage;
          
          // send the notification to the post user
          $parentCommentUser['real_time_notification'] = $event->reply->real_time_notification;
          $parentCommentUser->notify(new Reply);
    }
}
