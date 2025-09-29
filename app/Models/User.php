<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // ===== RELATIONS =====

    public function quizResults()
    {
        return $this->hasMany(Resultats::class, 'id_user');
    }

    public function attemptedQuizzes()
    {
        return $this->belongsToMany(Quiz::class, 'results', 'id_user', 'id_quiz')
                    ->withPivot('attempt_number', 'score', 'percentage', 'passed')
                    ->distinct();
    }

    // ===== MÉTHODES =====

    public function hasPassedQuiz($quizId)
    {
        return $this->quizResults()
                    ->where('id_quiz', $quizId)
                    ->where('passed', true)
                    ->exists();
    }

    public function getBestScoreForQuiz($quizId)
    {
        return $this->quizResults()
                    ->where('id_quiz', $quizId)
                    ->orderByDesc('score')
                    ->first();
    }

    public function getQuizStatsAttribute()
    {
        return [
            'total_attempts' => $this->quizResults()->count(),
            'passed_quizzes' => $this->quizResults()->where('passed', true)->distinct('id_quiz')->count(),
            'average_score' => round($this->quizResults()->avg('percentage'), 2),
            'best_score' => $this->quizResults()->max('percentage'),
        ];
    }
}
