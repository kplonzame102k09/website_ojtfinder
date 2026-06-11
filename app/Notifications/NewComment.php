<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    protected $user;
    protected $post;
    protected $commentContent;
    protected $isReply;

    public function __construct($user, $post, $commentContent, $isReply = false)
    {
        $this->user = $user;
        $this->post = $post;
        $this->commentContent = $commentContent;
        $this->isReply = $isReply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment',
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            
            // Dynamic message based on whether it's a reply or a direct comment
            'message' => $this->isReply ? 'replied to your comment' : 'commented on your post',
            'comment_body' => $this->commentContent,
            'post_id' => $this->post->id,
        ];
    }
}