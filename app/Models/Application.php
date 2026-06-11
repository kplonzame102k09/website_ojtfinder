<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
   protected $fillable = ['post_id', 'student_id', 'message', 'status'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
}
