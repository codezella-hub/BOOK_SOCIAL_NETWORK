<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBackBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
        'sentiment' // Pour utilisation future avec LLM
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relation avec le livre
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes utiles
    public function scopeWithHighRating($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeWithLowRating($query, $maxRating = 2)
    {
        return $query->where('rating', '<=', $maxRating);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Scope pour les sentiments (pour plus tard)
    public function scopePositive($query)
    {
        return $query->where('sentiment', 'positive');
    }

    public function scopeNeutral($query)
    {
        return $query->where('sentiment', 'neutral');
    }

    public function scopeNegative($query)
    {
        return $query->where('sentiment', 'negative');
    }

    public function scopeWithoutSentiment($query)
    {
        return $query->whereNull('sentiment');
    }

    // Validation de la note
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, $value));
    }

    // Méthodes utilitaires
    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function isHighRating()
    {
        return $this->rating >= 4;
    }

    public function isLowRating()
    {
        return $this->rating <= 2;
    }

    // Méthodes pour le sentiment (pour utilisation future avec LLM)
    public function getSentimentIconAttribute()
    {
        return match($this->sentiment) {
            'positive' => '😊',
            'negative' => '😞',
            'neutral' => '😐',
            default => '🤔' // Pas encore analysé
        };
    }

    public function getSentimentColorAttribute()
    {
        return match($this->sentiment) {
            'positive' => 'text-green-600',
            'negative' => 'text-red-600',
            'neutral' => 'text-yellow-600',
            default => 'text-gray-600' // Pas encore analysé
        };
    }

    public function hasSentimentAnalysis()
    {
        return !is_null($this->sentiment);
    }

    // Méthode pour mettre à jour le sentiment (à appeler plus tard avec LLM)
    public function updateSentiment($sentiment)
    {
        if (in_array($sentiment, ['positive', 'neutral', 'negative'])) {
            $this->update(['sentiment' => $sentiment]);
            return true;
        }
        return false;
    }
}
