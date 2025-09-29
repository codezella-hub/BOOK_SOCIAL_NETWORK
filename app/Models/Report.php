<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'reported_by',
        'postId',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postId');
    }
}
