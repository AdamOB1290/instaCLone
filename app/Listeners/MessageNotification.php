<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Notifications\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MessageNotification
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
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        // fetch the user who received the message
        $user = \App\User::findOrFail($event->chat->receiver_id);

        // create index for the notifier id within post user object
        $user['notifier_id'] = $event->chat->sender_id;

        // index the chat id
        $user['chat_id'] = (string)$event->chat->id;

        $user->notify(new Message);
    }
}
