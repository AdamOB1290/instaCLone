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

class LikeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $notification;
    // public $notifications;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $user)
    {
        // dd($this->user);
        $this->user = $user; // $this->user DOES NOT RETAIN $user var
        $this->notification = $user['real_time_notification'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('likes.' . $this->user->id);
    }

    // broadcastWith is the correct spelling
    public function broadcastWith()
    {

        return ['realTime_notification' => json_decode($this->notification)]; 

    }

}
