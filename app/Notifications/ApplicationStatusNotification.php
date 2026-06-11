<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $companyName;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $companyName)
    {
        $this->status = $status;
        $this->companyName = $companyName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // Storing in database allows the user to see it in their dashboard
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * This is what gets saved in the 'data' column of your notifications table.
     */
    public function toArray(object $notifiable): array
    {
        $message = $this->status === 'accepted' 
            ? "You have been accepted at {$this->companyName}!" 
            : "Your application at {$this->companyName} was not accepted.";

        return [
            'message' => $message,
            'status' => $this->status,
            'company_name' => $this->companyName,
            'sender_id' => auth()->id(),
        ];
    }
}