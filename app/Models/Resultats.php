<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Resultats extends Model
{
    use HasFactory;

    protected $table = 'results'; // Important : spécifier le nom de la table

    protected $fillable = [
        'id_quiz',
        'id_user',
        'attempt_number',
        'score',
        'percentage',
        'total_questions',
        'correct_answers',
        'passed',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'attempt_number' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'id_quiz' => 'integer',
        'id_user' => 'integer'
    ];

    // ===== RELATIONS =====

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

};
