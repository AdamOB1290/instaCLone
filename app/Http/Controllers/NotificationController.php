<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Notification;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function activityNotifications($userId) {
        $notifications = Notification::where('notifiable_id', $userId)->where('type', '!=', 'App\Notifications\Message')->get();

        foreach ($notifications as $notification) {
            $notification['notifier']= User::findOrFail($notification->data['notifier_id']);
        }
        // broadcast(new LikeEvent($notifications)); 

        // dd($notifications);
        return  $notifications;
    }

    public function messageNotifications($userId) {
        $notifications = Notification::where('notifiable_id', $userId)->where('type', 'App\Notifications\Message')->get();
        
        return  $notifications;
    }
}
