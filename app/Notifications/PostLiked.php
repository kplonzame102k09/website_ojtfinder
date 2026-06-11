<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    protected $user;
    protected $post;

    public function __construct($user, $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database']; // Stores in your 'notifications' table
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'like',
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'message' => 'liked your post',
            'post_id' => $this->post->id,
        ];
    }
}