<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $notification;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
        $this->notification = $post['real_time_notification'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $followedUsersIds= User::findOrFail($this->post->user_id)->followers;
        foreach ($followedUsersIds as $followedUserId) {
            return new PrivateChannel('activity.' . $followedUserId);
        }
    }

    // broadcastWith is the correct spelling
    public function broadcastWith()
    {

        return ['realTime_notification' => json_decode($this->notification)]; 

    }    
}
