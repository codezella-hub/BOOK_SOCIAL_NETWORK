@extends('layouts.admin-layout')

@section('title', 'Gestion des Donations')
@section('page-title', 'Donations de Livres')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Donations</h3>
                <p>{{ $stats['total'] }}</p>
            </div>
            <div class="stat-icon donations-icon">
                <i class="fas fa-heart"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>En attente</h3>
                <p>{{ $stats['pending'] }}</p>
            </div>
            <div class="stat-icon pending-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Approuvées</h3>
                <p>{{ $stats['approved'] }}</p>
            </div>
            <div class="stat-icon approved-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Rejetées</h3>
                <p>{{ $stats['rejected'] }}</p>
            </div>
            <div class="stat-icon rejected-icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Filtrer les Donations</h2>
        </div>
        
        <div class="filter-tabs">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Toutes
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
               class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> En attente
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" 
               class="filter-tab {{ request('status') === 'approved' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i> Approuvées
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" 
               class="filter-tab {{ request('status') === 'rejected' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i> Rejetées
            </a>
        </div>
    </div>

    <!-- Donations List -->
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">
                Donations 
                @if(request('status'))
                    - {{ ucfirst(request('status')) }}
                @endif
            </h2>
        </div>

        @if($donations->count() > 0)
            <div class="donations-grid">
                @foreach($donations as $donation)
                    <div class="donation-card">
                        <div class="donation-image">
                            @if($donation->book_image)
                                <img src="{{ asset('storage/' . $donation->book_image) }}" alt="{{ $donation->book_title }}">
                            @else
                                <div class="placeholder-image">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                        </div>

                        <div class="donation-content">
                            <div class="donation-header">
                                <h3>{{ $donation->book_title }}</h3>
                                <span class="status-badge status-{{ $donation->status }}">
                                    @if($donation->status === 'pending')
                                        <i class="fas fa-clock"></i> En attente
                                    @elseif($donation->status === 'approved')
                                        <i class="fas fa-check"></i> Approuvé
                                    @else
                                        <i class="fas fa-times"></i> Rejeté
                                    @endif
                                </span>
                            </div>

                            <p class="author">Par {{ $donation->author }}</p>
                            
                            @if($donation->genre)
                                <span class="genre-tag">{{ $donation->genre }}</span>
                            @endif

                            <div class="donation-meta">
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $donation->user->name }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-star"></i>
                                    <span class="condition condition-{{ $donation->condition }}">
                                        {{ ucfirst($donation->condition) }}
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $donation->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="donation-actions">
                                <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                
                                @if($donation->status === 'pending')
                                    <button onclick="showApprovalModal({{ $donation->id }}, {{ json_encode($donation->book_title) }})"
                                            class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Approuver
                                    </button>
                                    <button onclick="showRejectionModal({{ $donation->id }}, {{ json_encode($donation->book_title) }})"
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Rejeter
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $donations->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Aucune donation trouvée</h3>
                <p>
                    @if(request('status'))
                        Aucune donation avec le statut "{{ request('status') }}" n'a été trouvée.
                    @else
                        Il n'y a aucune donation pour le moment.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Approval Modal -->
    <div class="modal" id="approvalModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Approuver la Donation</h3>
                <button onclick="closeModal('approvalModal')" class="modal-close">&times;</button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir approuver la donation "<span id="approvalBookTitle"></span>" ?</p>
                    
                    <div class="form-group">
                        <label for="approval_notes">Notes (optionnel)</label>
                        <textarea id="approval_notes" name="admin_notes" class="form-control" rows="3" 
                                  placeholder="Ajoutez des notes concernant cette approbation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approuver
                    </button>
                    <button type="button" onclick="closeModal('approvalModal')" class="btn btn-secondary">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal" id="rejectionModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle"></i> Rejeter la Donation</h3>
                <button onclick="closeModal('rejectionModal')" class="modal-close">&times;</button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Pourquoi rejetez-vous la donation "<span id="rejectionBookTitle"></span>" ?</p>
                    
                    <div class="form-group">
                        <label for="rejection_notes">Raison du rejet *</label>
                        <textarea id="rejection_notes" name="admin_notes" class="form-control" rows="4" 
                                  placeholder="Expliquez la raison du rejet..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Rejeter
                    </button>
                    <button type="button" onclick="closeModal('rejectionModal')" class="btn btn-secondary">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .donations-icon { background: linear-gradient(45deg, #e74c3c, #c0392b); }
        .pending-icon { background: linear-gradient(45deg, #f39c12, #e67e22); }
        .approved-icon { background: linear-gradient(45deg, #27ae60, #229954); }
        .rejected-icon { background: linear-gradient(45deg, #e74c3c, #c0392b); }

        .alert {
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tab {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #6c757d;
            background: white;
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .filter-tab:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .filter-tab.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .donations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .donation-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .donation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .donation-image {
            height: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .donation-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-image {
            color: #adb5bd;
            font-size: 2rem;
        }

        .donation-content {
            padding: 1.5rem;
        }

        .donation-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .donation-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #2c3e50;
            flex: 1;
            margin-right: 1rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        .author {
            color: #6c757d;
            margin: 0 0 0.75rem 0;
            font-style: italic;
        }

        .genre-tag {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-bottom: 1rem;
        }

        .donation-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .meta-item i {
            width: 16px;
            text-align: center;
        }

        .condition {
            padding: 0.125rem 0.5rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .condition-excellent { background: #d4edda; color: #155724; }
        .condition-good { background: #d1ecf1; color: #0c5460; }
        .condition-fair { background: #fff3cd; color: #856404; }
        .condition-poor { background: #f8d7da; color: #721c24; }

        .donation-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .empty-icon {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6c757d;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        @media (max-width: 768px) {
            .donations-grid {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                flex-wrap: wrap;
            }

            .donation-header {
                flex-direction: column;
                gap: 0.5rem;
                align-items: stretch;
            }

            .donation-actions {
                justify-content: stretch;
            }

            .donation-actions .btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>

    <script>
        function showApprovalModal(donationId, bookTitle) {
            document.getElementById('approvalBookTitle').textContent = bookTitle;
            document.getElementById('approvalForm').action = `/admin/donations/${donationId}/approve`;
            document.getElementById('approvalModal').style.display = 'flex';
        }

        function showRejectionModal(donationId, bookTitle) {
            document.getElementById('rejectionBookTitle').textContent = bookTitle;
            document.getElementById('rejectionForm').action = `/admin/donations/${donationId}/reject`;
            document.getElementById('rejectionModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const approvalModal = document.getElementById('approvalModal');
            const rejectionModal = document.getElementById('rejectionModal');
            
            if (event.target === approvalModal) {
                approvalModal.style.display = 'none';
            }
            if (event.target === rejectionModal) {
                rejectionModal.style.display = 'none';
            }
        }
    </script>
@endsection