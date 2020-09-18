<?php

namespace App\Http\Controllers;



use App\Events\LikeEvent;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function activityNotifications($userId) {
        $notifications = Notification::where('notifiable_id', $userId)->where('type', '!=', 'App\Notifications\Message')->get();

        foreach ($notifications as $notification) {
            $notification['notifier']= User::findOrFail($notification->data['notifier_id']);  
        }
        return $notifications;
    }

    public function messageNotifications($userId) {
        $notifications = Notification::where('notifiable_id', $userId)->where('type', 'App\Notifications\Message')->get();
        
        return  $notifications;
    }

    public function markAllRead ($userId) {
        $sessionUser = User::findOrFail($userId);
        $sessionUser->unreadNotifications->markAsRead();
       
    }

    public function markAllUnread ($userId) {
        $sessionUser = User::findOrFail($userId);
        $sessionUser->readNotifications->markAsUnread();

       
    }

    public function markRead ($userId, $notifId) {
        $sessionUser = User::findOrFail($userId);
        $sessionUser->unreadNotifications->where('id', $notifId)->markAsRead();
    }

    public function markUnread ($userId, $notifId) {
        $sessionUser = User::findOrFail($userId);
        $sessionUser->readNotifications->where('id', $notifId)->markAsUnread(); 
    }
}
