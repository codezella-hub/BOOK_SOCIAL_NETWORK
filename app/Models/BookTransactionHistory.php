<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTransactionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'borrower_id',
        'lender_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'returned',
        'returned_approved',
        'status',
        'notes'
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'returned' => 'boolean',
        'returned_approved' => 'boolean',
    ];

    // Relation avec le livre
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relation avec l'emprunteur
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    // Relation avec le prêteur
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    // Scopes utiles
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', 'borrowed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Méthodes utilitaires
    public function isOverdue()
    {
        return $this->due_date < now() && $this->status === 'borrowed';
    }

    public function canBeReturned()
    {
        return $this->status === 'borrowed' && !$this->returned;
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }
}
