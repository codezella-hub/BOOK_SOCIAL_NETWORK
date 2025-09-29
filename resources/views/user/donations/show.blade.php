@extends('layouts.user-layout')

@section('title', $donation->book_title . ' - Détails de la Donation')

@section('content')
<div class="donation-show-page">
    <div class="container">
        <div class="page-header">
            <a href="{{ route('user.donations.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Retour à mes donations
            </a>
        </div>

        <div class="donation-details-container">
            <div class="donation-image-section">
                @if($donation->book_image)
                    <img src="{{ asset('storage/' . $donation->book_image) }}" alt="{{ $donation->book_title }}" class="donation-image">
                @else
                    <div class="donation-image placeholder">
                        <i class="fas fa-book"></i>
                        <p>Aucune image</p>
                    </div>
                @endif

                <div class="status-card">
                    <h3>Statut de la donation</h3>
                    <div class="status-badge status-{{ $donation->status }}">
                        @if($donation->status === 'pending')
                            <i class="fas fa-clock"></i>
                            <span>En attente de validation</span>
                        @elseif($donation->status === 'approved')
                            <i class="fas fa-check-circle"></i>
                            <span>Donation approuvée</span>
                        @else
                            <i class="fas fa-times-circle"></i>
                            <span>Donation rejetée</span>
                        @endif
                    </div>
                    
                    @if($donation->approved_at)
                        <div class="approval-info">
                            <p class="approval-date">
                                <i class="fas fa-calendar"></i>
                                {{ $donation->approved_at->format('d/m/Y à H:i') }}
                            </p>
                            @if($donation->approvedBy)
                                <p class="approval-by">
                                    <i class="fas fa-user"></i>
                                    Par {{ $donation->approvedBy->name }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="donation-info-section">
                <div class="book-header">
                    <h1>{{ $donation->book_title }}</h1>
                    <p class="author">Par <strong>{{ $donation->author }}</strong></p>
                    
                    @if($donation->genre)
                        <span class="genre-tag">
                            <i class="fas fa-tag"></i>
                            {{ $donation->genre }}
                        </span>
                    @endif
                </div>

                <div class="book-details">
                    <div class="detail-item">
                        <h3><i class="fas fa-star"></i> État du livre</h3>
                        <span class="condition condition-{{ $donation->condition }}">
                            @switch($donation->condition)
                                @case('excellent')
                                    <i class="fas fa-gem"></i> Excellent
                                    @break
                                @case('good')
                                    <i class="fas fa-thumbs-up"></i> Bon
                                    @break
                                @case('fair')
                                    <i class="fas fa-hand-paper"></i> Moyen
                                    @break
                                @case('poor')
                                    <i class="fas fa-exclamation-triangle"></i> Usagé
                                    @break
                            @endswitch
                        </span>
                    </div>

                    @if($donation->description)
                        <div class="detail-item">
                            <h3><i class="fas fa-align-left"></i> Description</h3>
                            <div class="description">
                                {{ $donation->description }}
                            </div>
                        </div>
                    @endif

                    <div class="detail-item">
                        <h3><i class="fas fa-calendar-plus"></i> Date de soumission</h3>
                        <p>{{ $donation->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($donation->admin_notes)
                        <div class="detail-item admin-notes">
                            <h3><i class="fas fa-comment-alt"></i> Notes de l'administrateur</h3>
                            <div class="admin-message {{ $donation->status === 'rejected' ? 'rejection' : 'approval' }}">
                                {{ $donation->admin_notes }}
                            </div>
                        </div>
                    @endif
                </div>

                @if($donation->status === 'pending')
                    <div class="action-buttons">
                        <a href="{{ route('user.donations.edit', $donation) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier la donation
                        </a>
                        <form action="{{ route('user.donations.destroy', $donation) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette donation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.donation-show-page {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.donation-details-container {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.donation-image-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.donation-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.donation-image.placeholder {
    height: 500px;
    background: #ecf0f1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #95a5a6;
    border-radius: 12px;
}

.donation-image.placeholder i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.status-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.status-card h3 {
    margin: 0 0 1rem 0;
    color: #2c3e50;
    font-size: 1.1rem;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 600;
    margin-bottom: 1rem;
}

.status-badge i {
    font-size: 1.2rem;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.approval-info {
    font-size: 0.9rem;
    color: #6c757d;
}

.approval-info p {
    margin: 0.25rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.donation-info-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.book-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f2f6;
}

.book-header h1 {
    color: #2c3e50;
    margin: 0 0 0.5rem 0;
    font-size: 2.5rem;
    line-height: 1.2;
}

.author {
    color: #7f8c8d;
    font-size: 1.2rem;
    margin: 0 0 1rem 0;
    font-style: italic;
}

.genre-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
}

.book-details {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.detail-item {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.detail-item h3 {
    margin: 0 0 0.75rem 0;
    color: #2c3e50;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-item h3 i {
    color: #3498db;
}

.condition {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
}

.condition-excellent { background: #2ecc71; color: white; }
.condition-good { background: #27ae60; color: white; }
.condition-fair { background: #f39c12; color: white; }
.condition-poor { background: #e74c3c; color: white; }

.description {
    background: white;
    padding: 1rem;
    border-radius: 6px;
    line-height: 1.6;
    color: #2c3e50;
    border: 1px solid #e9ecef;
}

.admin-notes {
    border-left-color: #e74c3c;
}

.admin-notes h3 i {
    color: #e74c3c;
}

.admin-message {
    background: white;
    padding: 1rem;
    border-radius: 6px;
    line-height: 1.6;
    border: 1px solid #e9ecef;
}

.admin-message.rejection {
    border-color: #e74c3c;
    background: #fdf2f2;
    color: #721c24;
}

.admin-message.approval {
    border-color: #27ae60;
    background: #f0f9f4;
    color: #155724;
}

.action-buttons {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f1f2f6;
    display: flex;
    gap: 1rem;
}

.action-buttons form.inline {
    display: inline;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1rem;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-1px);
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #3498db;
    border: 2px solid #3498db;
}

.btn-outline:hover {
    background: #3498db;
    color: white;
}

@media (max-width: 1024px) {
    .donation-details-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .donation-show-page {
        padding: 1rem 0;
    }

    .book-header h1 {
        font-size: 2rem;
    }

    .donation-info-section {
        padding: 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endsection