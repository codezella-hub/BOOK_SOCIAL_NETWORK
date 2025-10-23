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
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

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

    // ===== RELATIONS EXISTANTES =====
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

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function approvedDonations(): HasMany
    {
        return $this->hasMany(Donation::class, 'approved_by');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'created_by');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'liked_by');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_by');
    }

    public function evenementsOrganized() {
        return $this->hasMany(\App\Models\Evenement::class, 'user_id');
    }

    public function evenementsParticipating() {
        return $this->belongsToMany(\App\Models\Evenement::class, 'evenement_user')
            ->withPivot(['status','ticket_id'])
            ->withTimestamps();
    }

    public function tickets() {
        return $this->hasMany(\App\Models\Ticket::class);
    }

    // ===== NOUVELLES RELATIONS =====

    // Historique des emprunts où l'utilisateur est emprunteur
    public function borrowedBooks()
    {
        return $this->hasMany(BookTransactionHistory::class, 'borrower_id');
    }

    // Historique des prêts où l'utilisateur est prêteur
    public function lentBooks()
    {
        return $this->hasMany(BookTransactionHistory::class, 'lender_id');
    }

    // Feedbacks donnés par l'utilisateur
    public function bookFeedbacks()
    {
        return $this->hasMany(FeedBackBook::class);
    }

    // ===== MÉTHODES UTILITAIRES =====

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

    // Statistiques des livres
    public function getBookStatsAttribute()
    {
        return [
            'total_books' => $this->books()->count(),
            'shared_books' => $this->books()->where('shareable', true)->where('archived', false)->count(),
            'archived_books' => $this->books()->where('archived', true)->count(),
            'total_borrowed' => $this->borrowedBooks()->where('status', 'completed')->count(),
            'total_lent' => $this->lentBooks()->where('status', 'completed')->count(),
            'pending_requests' => $this->lentBooks()->where('status', 'pending')->count(),
            'total_feedbacks' => $this->bookFeedbacks()->count(),
        ];
    }

    // Vérifier si l'utilisateur peut emprunter un livre spécifique
    public function canBorrowBook(Book $book)
    {
        return $book->canBeBorrowed() &&
            $this->id !== $book->user_id && // Ne peut pas emprunter son propre livre
            !$this->hasPendingBorrowRequest($book);
    }

    // Vérifier si l'utilisateur a une demande d'emprunt en attente pour ce livre
    public function hasPendingBorrowRequest(Book $book)
    {
        return $this->borrowedBooks()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->exists();
    }

    // Vérifier si l'utilisateur a déjà donné un feedback pour un livre
    public function hasGivenFeedbackForBook(Book $book)
    {
        return $this->bookFeedbacks()
            ->where('book_id', $book->id)
            ->exists();
    }
}
