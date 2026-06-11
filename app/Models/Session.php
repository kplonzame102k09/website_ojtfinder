<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    // Sessions table doesn't use standard timestamps (created_at/updated_at)
    public $timestamps = false;

    // Explicitly set the primary key type if you are using the database driver
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    /**
     * Get the user that owns the session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to check if the session is still "active" (e.g., last 5 minutes)
     */
    public function isActive(): bool
    {
        return $this->last_activity >= now()->subMinutes(5)->getTimestamp();
    }
}