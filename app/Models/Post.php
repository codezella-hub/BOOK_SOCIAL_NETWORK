<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_P',
        'created_by',
        'topic_id',
    ];

        protected $casts = [
        'P_created_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function likes() 
    { 
        return $this->hasMany(Like::class, 'postId'); 
    }

    public function reports() 
    { 
        return $this->hasMany(Report::class, 'postId'); 
    }

    public function comments() 
    { 
        return $this->hasMany(Comment::class, 'postId'); 
    }

}
