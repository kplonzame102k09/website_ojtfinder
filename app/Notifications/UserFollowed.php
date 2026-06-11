<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    protected $follower;

    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database']; // Stores in your notifications table
    }

    public function toArray($notifiable)
    {
        return [
            'sender_id' => $this->follower->id,
            'sender_name' => $this->follower->name,
            'message' => 'started following you.',
            'type' => 'follow'
        ];
    }
}