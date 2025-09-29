<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'evenement_id','user_id','code','issued_at','pdf_path','expires_at','redeemed_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function evenement(): BelongsTo { return $this->belongsTo(Evenement::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
