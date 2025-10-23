<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['liked_by','commentId'];

    protected $casts = [
        'CL_created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'liked_by');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'commentId');
    }
}
