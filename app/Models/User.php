<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\StudentRequirement;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'bio',
        'last_seen_at',
        'password',
        'contact_number',
        'address',
        'gender',
        'birthday',
        'profile_picture',
        'slug',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * NEW HELPER: Get the profile picture URL via the Proxy Route.
     * Usage in Blade: <img src="{{ $user->profile_picture_url }}">
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return route('image.display', ['path' => $this->profile_picture]);
        }

        // This generates a blue-themed avatar with the user's name initials automatically
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=1e293b&color=3b82f6&bold=true";
    }

    // --- Relationships ---

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function applications()
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function messages()
    {
        return $this->sentMessages->merge($this->receivedMessages);
    }

    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->whereNull('read_at')->count();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }
    public function sessions()
    {
        // This ensures the first() session is always the most recent one
        return $this->hasMany(Session::class)->latest('last_activity');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }
    public function requirements()
    {
        return $this->hasMany(StudentRequirement::class);
    }

    public function isNearby($otherUser) 
    {
        if (!$this->address || !$otherUser->address) return false;
        return strtolower(trim($this->address)) === strtolower(trim($otherUser->address));
    }
    public function isOnline()
    {
        // If the date is null, they've never been seen
        if (!$this->last_seen_at) return false;

        // Is the 'last_seen_at' time greater than (after) 5 minutes ago?
        return $this->last_seen_at->gt(now()->subMinutes(5));
    }
    // --- Route Logic ---

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->slug = Str::slug($user->name) . '-' . uniqid();
        });
    }
}