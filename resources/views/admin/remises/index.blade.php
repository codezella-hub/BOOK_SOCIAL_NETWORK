@extends('layouts.admin-layout')

@section('title', 'Gestion des Remises')
@section('page-title', 'Gestion des Remises')

@section('content')
<div class="remises-management-page">
    <div class="container">
        <!-- En-tête -->
        <div class="page-header">
            <h1><i class="fas fa-handshake"></i> Gestion des remises</h1>
            <p class="subtitle">Gérez les demandes de remise de livres</p>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filtres -->
        <div class="filters-section">
            <div class="filter-controls">
                <select class="filter-select" id="filter-status">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="prevu">Prévu</option>
                    <option value="effectue">Effectué</option>
                    <option value="annule">Annulé</option>
                </select>
                
                <div class="stats-summary">
                    <span class="stat-item">
                        <strong>{{ $remises->total() }}</strong> remise(s) au total
                    </span>
                </div>
            </div>
        </div>

        <!-- Liste des remises -->
        @if($remises->count() > 0)
            <div class="remises-grid">
                @foreach($remises as $remise)
                    <div class="remise-card" data-status="{{ $remise->statut }}">
                        <!-- En-tête de la carte -->
                        <div class="card-header">
                            <div class="book-info">
                                <h3>{{ $remise->donation->book_title }}</h3>
                                <p class="author">Par {{ $remise->donation->author }}</p>
                            </div>
                            <span class="status-badge status-{{ $remise->statut }}">
                                @switch($remise->statut)
                                    @case('en_attente')
                                        <i class="fas fa-clock"></i> En attente
                                        @break
                                    @case('prevu')
                                        <i class="fas fa-calendar-check"></i> Prévu
                                        @break
                                    @case('effectue')
                                        <i class="fas fa-check-circle"></i> Effectué
                                        @break
                                    @case('annule')
                                        <i class="fas fa-times-circle"></i> Annulé
                                        @break
                                @endswitch
                            </span>
                        </div>

                        <!-- Contenu de la carte -->
                        <div class="card-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label><i class="fas fa-user"></i> Donateur</label>
                                    <value>{{ $remise->user->name }}</value>
                                    <small>{{ $remise->user->email }}</small>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="fas fa-calendar"></i> Date RDV</label>
                                    <value>{{ $remise->date_rendez_vous_formatted }}</value>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="fas fa-map-marker-alt"></i> Lieu</label>
                                    <value>{{ $remise->lieu }}</value>
                                </div>
                                
                                @if($remise->admin)
                                    <div class="info-item">
                                        <label><i class="fas fa-user-shield"></i> Admin responsable</label>
                                        <value>{{ $remise->admin->name }}</value>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($remise->statut === 'en_attente')
                            <div class="card-actions">
                                <div class="action-buttons">
                                    <!-- Bouton Confirmer -->
                                    <form method="POST" action="{{ route('admin.remises.updateStatus', $remise->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="statut" value="prevu">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Confirmer
                                        </button>
                                    </form>
                                    
                                    <!-- Bouton Proposer nouvelle date -->
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            onclick="showRescheduleModal({{ $remise->id }}, '{{ $remise->donation->book_title }}')">
                                        <i class="fas fa-calendar-alt"></i> Replanifier
                                    </button>
                                    
                                    <!-- Bouton Annuler -->
                                    <form method="POST" action="{{ route('admin.remises.updateStatus', $remise->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="statut" value="annule">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette remise ?')">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($remise->statut === 'prevu')
                            <div class="card-actions">
                                <form method="POST" action="{{ route('admin.remises.updateStatus', $remise->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="statut" value="effectue">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-handshake"></i> Marquer comme effectué
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($remises->hasPages())
                <div class="pagination-wrapper">
                    {{ $remises->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-handshake"></i>
                <h3>Aucune remise planifiée</h3>
                <p>Les demandes de remise apparaîtront ici une fois soumises par les donateurs.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de replanification -->
<div id="rescheduleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Replanifier la remise</h4>
            <span class="close" onclick="closeRescheduleModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Livre : <strong id="rescheduleBookTitle"></strong></p>
            
            <form id="rescheduleForm" method="POST">
                @csrf
                <input type="hidden" name="statut" value="prevu">
                
                <div class="form-group">
                    <label for="new_date">Nouvelle date et heure</label>
                    <input type="datetime-local" id="new_date" name="date_rendez_vous" required>
                </div>
                
                <div class="form-group">
                    <label for="new_lieu">Nouveau lieu (optionnel)</label>
                    <input type="text" id="new_lieu" name="lieu" placeholder="Laisser vide pour garder le lieu actuel">
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="closeRescheduleModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer la nouvelle date</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.remises-management-page {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
}

.page-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

.filters-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.filter-select {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
}

.stats-summary {
    color: #6c757d;
    font-size: 0.9rem;
}

.remises-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.remise-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s ease;
}

.remise-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-header {
    padding: 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.book-info h3 {
    color: #2c3e50;
    margin: 0 0 0.25rem 0;
    font-size: 1.2rem;
}

.book-info .author {
    color: #6c757d;
    margin: 0;
    font-style: italic;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-en_attente {
    background: #fff3cd;
    color: #856404;
}

.status-prevu {
    background: #d1ecf1;
    color: #0c5460;
}

.status-effectue {
    background: #d4edda;
    color: #155724;
}

.status-annule {
    background: #f8d7da;
    color: #721c24;
}

.card-content {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
}

.info-item value {
    display: block;
    color: #2c3e50;
    font-size: 0.95rem;
}

.info-item small {
    display: block;
    color: #6c757d;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.card-actions {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-outline {
    background: transparent;
    color: #6c757d;
    border: 1px solid #ddd;
}

.btn-outline:hover {
    background: #f8f9fa;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: #495057;
}

.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h4 {
    margin: 0;
    color: #2c3e50;
}

.close {
    font-size: 1.5rem;
    cursor: pointer;
    color: #6c757d;
}

.close:hover {
    color: #2c3e50;
}

.modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .remises-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Filtre par statut
document.getElementById('filter-status').addEventListener('change', function() {
    const selectedStatus = this.value;
    const cards = document.querySelectorAll('.remise-card[data-status]');
    
    cards.forEach(card => {
        if (!selectedStatus || card.dataset.status === selectedStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});

// Modal de replanification
function showRescheduleModal(remiseId, bookTitle) {
    document.getElementById('rescheduleBookTitle').textContent = bookTitle;
    document.getElementById('rescheduleForm').action = `/admin/remises/${remiseId}/status`;
    
    // Définir la date minimale à maintenant + 2 heures
    const now = new Date();
    now.setHours(now.getHours() + 2);
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('new_date').setAttribute('min', minDateTime);
    
    document.getElementById('rescheduleModal').style.display = 'flex';
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'none';
    document.getElementById('rescheduleForm').reset();
}

// Fermer le modal en cliquant en dehors
document.getElementById('rescheduleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRescheduleModal();
    }
});
</script>
@endsection