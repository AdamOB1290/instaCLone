<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Like extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toDatabase($notifiable)
    {
        $index = $notifiable->liked_object_index;
        $realTime = json_decode($notifiable->real_time_notification);
        if (isset($realTime->data->post_id)) {
            return [
                'post_id' => $realTime->data->post_id,
                'notification_message' => $notifiable->notification_message,
                'notifier_id' => $notifiable->liker_id,
                'object_id' => $notifiable->liked_object,
            ];
        } else {
            return [
                'notification_message' => $notifiable->notification_message,
                'notifier_id' => $notifiable->liker_id,
                'object_id' => $notifiable->liked_object,
            ];
        }
    

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
