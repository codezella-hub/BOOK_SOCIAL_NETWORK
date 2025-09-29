<?php

namespace App\Models;

use App\Models\Donation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable,HasRoles;

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
    /**
     * Get the donations made by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Donation>
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the donations approved by this admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Donation>
     */
    public function approvedDonations(): HasMany
    {
        return $this->hasMany(Donation::class, 'approved_by');
    }


    public function books()
    {
        return $this->hasMany(Book::class);
    }



    //User create many posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    //User create many comments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'created_by');
    }

    //User give many likes
    public function likes()
    {
        return $this->hasMany(Like::class, 'liked_by');
    }

    //User make many reports
    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_by');
    }



}
