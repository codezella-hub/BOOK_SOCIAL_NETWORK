<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Remise extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'user_id',
        'admin_id',
        'date_rendez_vous',
        'lieu',
        'statut'
    ];

    protected $casts = [
        'date_rendez_vous' => 'datetime',
    ];

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_PREVU = 'prevu';
    const STATUT_EFFECTUE = 'effectue';
    const STATUT_ANNULE = 'annule';

    public static function getStatutOptions()
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_PREVU => 'Prévu',
            self::STATUT_EFFECTUE => 'Effectué',
            self::STATUT_ANNULE => 'Annulé',
        ];
    }

    // Relations
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accesseurs
    public function getStatutLabelAttribute()
    {
        return self::getStatutOptions()[$this->statut] ?? $this->statut;
    }

    public function getDateRendezVousFormattedAttribute()
    {
        return $this->date_rendez_vous->format('d/m/Y à H:i');
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    public function scopePrevu($query)
    {
        return $query->where('statut', self::STATUT_PREVU);
    }

    public function scopeEffectue($query)
    {
        return $query->where('statut', self::STATUT_EFFECTUE);
    }
}
