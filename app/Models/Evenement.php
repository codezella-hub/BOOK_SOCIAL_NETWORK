<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evenement extends Model
{
    // app/Models/Evenement.php
protected $fillable = [
    'user_id','title','slug','summary','description','starts_at','ends_at','timezone',
    'location_text','status','visibility','capacity','cover_image_path','published_at','cancelled_at',
    'lat','lng', 
];


    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function organizer(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants(): BelongsToMany {
        return $this->belongsToMany(User::class, 'evenement_user')
            ->withPivot(['status','ticket_id'])
            ->withTimestamps();
    }

    public function tickets(): HasMany {
        return $this->hasMany(Ticket::class);
    }

    // Scopes
    public function scopePublished(Builder $q): Builder { return $q->where('status', 'published'); }
    public function scopeUpcoming(Builder $q): Builder { return $q->where('starts_at', '>=', now()); }
    public function scopeVisibleTo(Builder $q, ?User $user): Builder {
        return $q->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->orWhere(function ($q) use ($user) {
                  if ($user) $q->where('user_id', $user->id);
              });
        });
    }

    public function getCapacityRemainingAttribute(): ?int {
        if (is_null($this->capacity)) return null;
        $going = $this->participants()->wherePivot('status', 'going')->count();
        return max(0, $this->capacity - $going);
    }

    public function isPublished(): bool { return $this->status === 'published'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
public function scopeFuture(Builder $q): Builder
{
    return $q->where('starts_at', '>=', now());
}

public function scopeWithinRadius(Builder $q, float $lat, float $lng, float $radiusKm): Builder
{
    // Haversine distance in kilometers
    $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat))))";

    return $q->whereNotNull('lat')
             ->whereNotNull('lng')
             ->select('*')
             ->selectRaw("$haversine as distance_km", [$lat, $lng, $lat])
             ->whereRaw("$haversine <= ?", [$lat, $lng, $lat, $radiusKm])
             ->orderBy('distance_km');
}
}
