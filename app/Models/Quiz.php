<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_quiz';

    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'nb_questions',
        'max_attempts',
        'time_limit',
        'is_active',
        'id_book'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nb_questions' => 'integer',
        'max_attempts' => 'integer',
        'time_limit' => 'integer',
        'id_book' => 'integer'
    ];

    // ===== RELATIONS =====

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_quiz', 'id_quiz')
                    ->orderBy('order_position');
    }

    public function results()
    {
        return $this->hasMany(Resultats::class, 'id_quiz', 'id_quiz');
    }

// ===== GESTION DES LIVRES =====

    public static function getBookOptions()
    {
        return [
            1 => 'Harry Potter - Tome 1',
            2 => 'Harry Potter - Tome 2',
            3 => 'Le Seigneur des Anneaux',
            4 => 'Game of Thrones',
            5 => 'Les Misérables',
            6 => '1984',
            7 => 'Le Petit Prince'
        ];
    }

    public function getBookNameAttribute()
    {
        $books = self::getBookOptions();
        return $books[$this->id_book] ?? 'Livre inconnu';
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // ===== MÉTHODES UTILITAIRES =====

    public function getDifficultyLabelAttribute()
    {
        $levels = [
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé'
        ];

        return $levels[$this->difficulty_level] ?? $this->difficulty_level;
    }

    public function getDifficultyBadgeClassAttribute()
    {
        $classes = [
            'beginner' => 'badge-success',
            'intermediate' => 'badge-warning',
            'advanced' => 'badge-danger'
        ];

        return $classes[$this->difficulty_level] ?? 'badge-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge badge-success">Actif</span>'
            : '<span class="badge badge-secondary">Inactif</span>';
    }

    public function getFormattedTimeLimitAttribute()
    {
        $minutes = $this->time_limit;

        if ($minutes < 60) {
            return $minutes . ' min';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }

        return $hours . 'h ' . $remainingMinutes . 'min';
    }

    // ===== STATISTIQUES =====

    public function getTotalAttemptsAttribute()
    {
        return $this->results()->count();
    }

    public function getSuccessRateAttribute()
    {
        $total = $this->results()->count();
        if ($total === 0) return 0;

        $passed = $this->results()->where('passed', true)->count();
        return round(($passed / $total) * 100, 1);
    }

    public function getAverageScoreAttribute()
    {
        return round($this->results()->avg('percentage') ?? 0, 1);
    }
}



