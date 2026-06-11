<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Allows these fields to be filled via Comment::create()
    protected $fillable = [
        'user_id', 
        'post_id', 
        'content'
        ];

     //Get the user that owns the comment.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //Get the post that owns the comment.
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}