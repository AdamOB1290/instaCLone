<?php

namespace App\Listeners;

use App\Events\SharePost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShareNotification
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
     * @param  SharePost  $event
     * @return void
     */
    public function handle(SharePost $event)
    {
        //
    }
}
