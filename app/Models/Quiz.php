<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    // ✅ Définir la clé primaire personnalisée
    protected $primaryKey = 'id_quiz';

    // ✅ Indiquer que la clé est auto-incrémentée
    public $incrementing = true;

    // ✅ Indiquer que c’est un entier (sinon Laravel le prend comme string)
    protected $keyType = 'int';

    // ✅ Utiliser 'id_quiz' pour le route model binding
    public function getRouteKeyName()
    {
        return 'id_quiz';
    }

    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'nb_questions',
        'max_attempts',
        'time_limit',
        'is_active',
        'id_book',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nb_questions' => 'integer',
        'max_attempts' => 'integer',
        'time_limit' => 'integer',
        'id_book' => 'integer',
    ];

    // ===== RELATIONS =====

    // Un quiz appartient à un livre
    public function book()
    {
        return $this->belongsTo(Book::class, 'id_book', 'id');
    }

    // Un quiz a plusieurs questions
    public function questions()
    {
        return $this->hasMany(Question::class, 'id_quiz', 'id_quiz')
                    ->orderBy('order_position');
    }

    // Résultats des utilisateurs
    public function results()
    {
        return $this->hasMany(Resultats::class, 'id_quiz', 'id_quiz');
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
            'advanced' => 'Avancé',
        ];

        return $levels[$this->difficulty_level] ?? $this->difficulty_level;
    }

    public function getDifficultyBadgeClassAttribute()
    {
        $classes = [
            'beginner' => 'badge-success',
            'intermediate' => 'badge-warning',
            'advanced' => 'badge-danger',
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

        return $remainingMinutes === 0
            ? "{$hours}h"
            : "{$hours}h {$remainingMinutes}min";
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
