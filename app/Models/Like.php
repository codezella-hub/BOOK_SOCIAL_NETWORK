<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'liked_by',
        'postId',
    ];
    
    protected $casts = [
        'L_created_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'liked_by');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postId');
    }
}
