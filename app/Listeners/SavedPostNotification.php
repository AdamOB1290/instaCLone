<?php

namespace App\Listeners;

use App\Events\SavedPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SavedPostNotification
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
     * @param  SavedPost  $event
     * @return void
     */
    public function handle(SavedPost $event)
    {
        //
    }
}
