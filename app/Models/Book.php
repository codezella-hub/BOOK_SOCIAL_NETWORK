<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_name',
        'isbn',
        'synopsis',
        'book_cover',
        'archived',
        'shareable',
        'category_id',
        'user_id' // Ajouté
    ];

    protected $casts = [
        'archived' => 'boolean',
        'shareable' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
