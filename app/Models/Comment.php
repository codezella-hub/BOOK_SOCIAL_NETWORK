<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_C',
        'created_by',
        'postId',
        'parentId',
    ];

        protected $casts = [
        'C_created_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postId');
    }
    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'commentId');
    }

    public function isLikedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->likes()->where('liked_by', $userId)->exists();
    }
    public function parent() { return $this->belongsTo(Comment::class, 'parentId'); }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parentId')
                    ->with(['user'])            // charge l’auteur
                    ->withCount('likes')        // compteur de likes
                    ->orderBy('C_created_at');  // du plus ancien au plus récent
    }

    // Scope pratique pour les top-level
    public function scopeTopLevel($q) { return $q->whereNull('parentId'); }
}
