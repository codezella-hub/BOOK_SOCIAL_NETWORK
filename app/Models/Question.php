<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_quiz',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'order_position',
        'explanation'
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'order_position' => 'integer',
        'id_quiz' => 'integer'
    ];

    // ===== RELATIONS =====

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

};
