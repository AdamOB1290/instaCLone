<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteStory implements ShouldQueue
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
        sleep(120); // waits 10 seconds before executing the next line

        // if the post type is 'story' delete, if it's 'post/story' update
        if ($event->post->type == 'story') {
            $event->post->delete();
        } else if ($event->post->type == 'post/story' || $event->post->type == 'story/post') {
            $event->post->update(array('type' => 'post'));
        }
    }
}
