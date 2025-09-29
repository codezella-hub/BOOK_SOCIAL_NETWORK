<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];
    protected $casts = [
        'T_created_at' => 'datetime', 
    ];
    // Relations


public function posts()
{
    return $this->hasMany(Post::class);
}
}
