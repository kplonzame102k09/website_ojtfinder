<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'file',
        'training_category',
    ];
    //Relation to user
    public function user()
    {
        return $this->belongsTo(User::class);    
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // A helper method to check if the current user liked the post
    public function isLikedByUser()
    {
        return $this->likes->contains('user_id', auth()->id());
    }
    public function applications()
{
    // If a post has many applications
    return $this->hasMany(Application::class);
}
}