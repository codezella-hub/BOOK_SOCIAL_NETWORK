@extends('layouts.user-layout')

@section('title', 'Mes Donations de Livres')

@section('content')
<div class="donations-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-heart"></i> Mes Donations de Livres</h1>
            <div class="header-actions">
                <a href="{{ route('chatbot.index') }}" class="btn btn-ai">
                    <i class="fas fa-robot"></i> Assistant IA Livres
                </a>
                <a href="{{ route('user.donations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Donner un Livre
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($donations->count() > 0)
            <div class="donations-grid">
                @foreach($donations as $donation)
                    <div class="donation-card">
                        @if($donation->book_image)
                            <div class="donation-image">
                                <img src="{{ asset('storage/' . $donation->book_image) }}" alt="{{ $donation->book_title }}">
                            </div>
                        @else
                            <div class="donation-image placeholder">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif

                        <div class="donation-info">
                            <h3>{{ $donation->book_title }}</h3>
                            <p class="author">Par {{ $donation->author }}</p>

                            @if($donation->genre)
                                <span class="genre-tag">{{ $donation->genre }}</span>
                            @endif

                            <div class="condition-status">
                                <span class="condition condition-{{ $donation->condition }}">
                                    {{ ucfirst($donation->condition) }}
                                </span>
                                <span class="status status-{{ $donation->status }}">
                                    @if($donation->status === 'pending')
                                        <i class="fas fa-clock"></i> En attente
                                    @elseif($donation->status === 'approved')
                                        <i class="fas fa-check"></i> Approuvé
                                    @else
                                        <i class="fas fa-times"></i> Rejeté
                                    @endif
                                </span>
                            </div>

                            <div class="donation-actions">
                                <a href="{{ route('user.donations.show', $donation) }}" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                @if($donation->status === 'pending')
                                    <a href="{{ route('user.donations.edit', $donation) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="{{ route('user.donations.destroy', $donation) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette donation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $donations->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Aucune donation pour le moment</h3>
                <p>Partagez vos livres avec la communauté en faisant votre première donation !</p>
                <a href="{{ route('user.donations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Donner un Livre
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.donations-page {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.page-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin: 0;
}

.page-header h1 i {
    color: #e74c3c;
}

.alert {
    padding: 1rem;
    margin-bottom: 2rem;
    border-radius: 0.5rem;
    border: none;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.donations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.donation-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.donation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.donation-image {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.donation-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.donation-image.placeholder {
    background: #ecf0f1;
    color: #95a5a6;
}

.donation-image.placeholder i {
    font-size: 3rem;
}

.donation-info {
    padding: 1.5rem;
}

.donation-info h3 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    font-size: 1.25rem;
}

.author {
    color: #7f8c8d;
    margin: 0 0 1rem 0;
    font-style: italic;
}

.genre-tag {
    display: inline-block;
    background: #3498db;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

.condition-status {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.condition, .status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.condition-excellent { background: #2ecc71; color: white; }
.condition-good { background: #27ae60; color: white; }
.condition-fair { background: #f39c12; color: white; }
.condition-poor { background: #e74c3c; color: white; }

.status-pending { background: #f39c12; color: white; }
.status-approved { background: #27ae60; color: white; }
.status-rejected { background: #e74c3c; color: white; }

.donation-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.donation-actions form.inline {
    display: inline;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-outline {
    background: transparent;
    color: #3498db;
    border: 1px solid #3498db;
}

.btn-outline:hover {
    background: #3498db;
    color: white;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-ai {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-ai:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 4rem;
    color: #e74c3c;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #7f8c8d;
    margin-bottom: 2rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .header-actions {
        flex-direction: column;
        width: 100%;
        gap: 0.5rem;
    }

    .header-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .donations-grid {
        grid-template-columns: 1fr;
    }

    .donation-actions {
        flex-wrap: wrap;
    }
}
</style>
@endsection
