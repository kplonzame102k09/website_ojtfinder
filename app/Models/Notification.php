<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model // Changed from Notifications to Notification
{
    use HasFactory;

    // Laravel uses 'notifications' table by default
    protected $table = 'notifications';

    // Notifications use UUIDs as primary keys by default
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id', 
        'data',
        'read_at',
    ];

    //Data is stored as JSON in the database.
    //This cast ensures you can access $notification->data['sender_name'] easily.
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    //Polymorphic relationship
    //This connects the notification to whoever is receiving it (User)
    public function notifiable()
    {
        return $this->morphTo();
    }
}