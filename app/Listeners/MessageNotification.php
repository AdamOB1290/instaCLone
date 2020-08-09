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

        // get the username of the sender
        $username = \App\User::findOrFail($event->chat->sender_id)->username;

        // index the sender username
        $user['sender_username'] = $username;

        // index the chat id
        $user['chat_id'] = (string)$event->chat->id;

        $user->notify(new Message);
    }
}
