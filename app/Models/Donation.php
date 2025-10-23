<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'book_title',
        'author',
        'description',
        'genre',
        'condition',
        'book_image',
    ];

    protected static function booted()
    {
        static::created(function ($donation) {
            // Automatically create approval status when donation is created
            ApprovalStatus::create([
                'donation_id' => $donation->id,
                'status' => 'pending'
            ]);
        });
    }

    // No casts needed now

    /**
     * Get the user who donated the book
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the approval status for this donation
     */
    public function approvalStatus(): HasOne
    {
        return $this->hasOne(ApprovalStatus::class);
    }

    /**
     * Get the remise for this donation
     */
    public function remise(): HasOne
    {
        return $this->hasOne(Remise::class);
    }

    /**
     * Scope to get only pending donations
     */
    public function scopePending($query)
    {
        return $query->whereHas('approvalStatus', function ($q) {
            $q->where('status', 'pending');
        });
    }

    /**
     * Scope to get only approved donations
     */
    public function scopeApproved($query)
    {
        return $query->whereHas('approvalStatus', function ($q) {
            $q->where('status', 'approved');
        });
    }

    /**
     * Scope to get only rejected donations
     */
    public function scopeRejected($query)
    {
        return $query->whereHas('approvalStatus', function ($q) {
            $q->where('status', 'rejected');
        });
    }

    /**
     * Get the status of this donation from its approval status
     */
    public function getStatusAttribute()
    {
        return $this->approvalStatus ? $this->approvalStatus->status : 'pending';
    }

    /**
     * Get the admin notes from approval status
     */
    public function getAdminNotesAttribute()
    {
        return $this->approvalStatus ? $this->approvalStatus->admin_notes : null;
    }

    /**
     * Get the approved at date from approval status
     */
    public function getApprovedAtAttribute()
    {
        return $this->approvalStatus ? $this->approvalStatus->approved_at : null;
    }

    /**
     * Get the user who approved this donation through the approval status
     */
    public function getApprovedByAttribute()
    {
        return $this->approvalStatus && $this->approvalStatus->approvedBy 
            ? $this->approvalStatus->approvedBy 
            : null;
    }
}
