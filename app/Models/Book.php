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
        'user_id'
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

    // Nouvelle relation avec l'historique des transactions
    public function transactionHistories()
    {
        return $this->hasMany(BookTransactionHistory::class);
    }

    // Relation avec les feedbacks
    public function feedbacks()
    {
        return $this->hasMany(FeedBackBook::class);
    }

    // Scope pour les livres disponibles (partageables et non archivés)
    public function scopeAvailable($query)
    {
        return $query->where('shareable', true)
            ->where('archived', false);
    }

    // Méthodes utilitaires
    public function isCurrentlyBorrowed()
    {
        return $this->transactionHistories()
            ->where('status', 'borrowed')
            ->exists();
    }

    // CORRECTION : Supprimer la référence à 'approved' qui n'existe plus
    public function averageRating()
    {
        return $this->feedbacks()->avg('rating');
    }

    // CORRECTION : Supprimer la référence à 'approved' qui n'existe plus
    public function totalReviews()
    {
        return $this->feedbacks()->count();
    }

    public function canBeBorrowed()
    {
        return $this->shareable &&
            !$this->archived &&
            !$this->isCurrentlyBorrowed();
    }

    // Nouvelles méthodes pour les statistiques
    public function getRatingStatsAttribute()
    {
        $stats = [
            'average' => round($this->feedbacks()->avg('rating') ?? 0, 1),
            'total' => $this->feedbacks()->count(),
            'distribution' => [
                5 => $this->feedbacks()->where('rating', 5)->count(),
                4 => $this->feedbacks()->where('rating', 4)->count(),
                3 => $this->feedbacks()->where('rating', 3)->count(),
                2 => $this->feedbacks()->where('rating', 2)->count(),
                1 => $this->feedbacks()->where('rating', 1)->count(),
            ]
        ];

        // Calculer les pourcentages
        if ($stats['total'] > 0) {
            foreach ($stats['distribution'] as $rating => $count) {
                $stats['distribution_percent'][$rating] = round(($count / $stats['total']) * 100, 1);
            }
        }

        return $stats;
    }

    public function getRecentFeedbacks($limit = 5)
    {
        return $this->feedbacks()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    // Vérifier si un utilisateur peut donner un feedback pour ce livre
    public function canUserGiveFeedback($userId)
    {
        // L'utilisateur ne peut pas donner de feedback sur son propre livre
        if ($this->user_id == $userId) {
            return false;
        }

        // Vérifier si l'utilisateur a déjà donné un feedback
        return !$this->feedbacks()
            ->where('user_id', $userId)
            ->exists();
    }
}
