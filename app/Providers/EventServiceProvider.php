<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // 'App\Events\PostCreated' => [
        //     'App\Listeners\DeleteStory',
        // ],
        'App\Events\UserFollowed' => [
            'App\Listeners\FollowNotification',
        ],
        'App\Events\PostCreated' => [
            'App\Listeners\PostNotification',
            'App\Listeners\DeleteStory',
        ],
        'App\Events\CommentCreated' => [
            'App\Listeners\CommentNotification',
        ],
        'App\Events\ReplyCreated' => [
            'App\Listeners\ReplyNotification',
        ],
        'App\Events\SavedPost' => [
            'App\Listeners\SavedPostNotification',
        ],
        'App\Events\LikeEvent' => [
            'App\Listeners\LikeNotification',
        ],
        'App\Events\SharePost' => [
            'App\Listeners\ShareNotification',
        ],
        'App\Events\MessageSent' => [
            'App\Listeners\MessageNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
