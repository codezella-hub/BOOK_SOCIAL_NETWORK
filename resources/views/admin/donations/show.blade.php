@extends('layouts.admin-layout')

@section('title', 'Détails de la Donation - ' . $donation->book_title)
@section('page-title', 'Détails de la Donation')

@section('content')
    <div class="page-header-actions">
        <a href="{{ route('admin.donations.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        
        @if($donation->status === 'pending')
            <div class="quick-actions">
                <button onclick="showApprovalModal({{ $donation->id }}, {{ json_encode($donation->book_title) }})" 
                        class="btn btn-success">
                    <i class="fas fa-check"></i> Approuver
                </button>
                <button onclick="showRejectionModal({{ $donation->id }}, {{ json_encode($donation->book_title) }})" 
                        class="btn btn-danger">
                    <i class="fas fa-times"></i> Rejeter
                </button>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="donation-details-grid">
        <!-- Book Information Card -->
        <div class="details-card">
            <div class="card-header">
                <h3><i class="fas fa-book"></i> Informations du Livre</h3>
            </div>
            <div class="card-content">
                <div class="book-image">
                    @if($donation->book_image)
                        <img src="{{ asset('storage/' . $donation->book_image) }}" alt="{{ $donation->book_title }}">
                    @else
                        <div class="placeholder-image">
                            <i class="fas fa-book"></i>
                            <p>Aucune image</p>
                        </div>
                    @endif
                </div>
                
                <div class="book-info">
                    <div class="info-item">
                        <label>Titre</label>
                        <value>{{ $donation->book_title }}</value>
                    </div>
                    
                    <div class="info-item">
                        <label>Auteur</label>
                        <value>{{ $donation->author }}</value>
                    </div>
                    
                    @if($donation->genre)
                        <div class="info-item">
                            <label>Genre</label>
                            <value>
                                <span class="genre-tag">{{ $donation->genre }}</span>
                            </value>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <label>État</label>
                        <value>
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
                        </value>
                    </div>
                    
                    @if($donation->description)
                        <div class="info-item">
                            <label>Description</label>
                            <value class="description">{{ $donation->description }}</value>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Donation Status Card -->
        <div class="details-card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Statut de la Donation</h3>
            </div>
            <div class="card-content">
                <div class="status-display">
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
                    
                    <div class="status-timeline">
                        <div class="timeline-item active">
                            <div class="timeline-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Donation soumise</h4>
                                <p>{{ $donation->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($donation->approved_at)
                            <div class="timeline-item active">
                                <div class="timeline-icon {{ $donation->status === 'approved' ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $donation->status === 'approved' ? 'check' : 'times' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>
                                        @if($donation->status === 'approved')
                                            Donation approuvée
                                        @else
                                            Donation rejetée
                                        @endif
                                    </h4>
                                    <p>{{ $donation->approved_at->format('d/m/Y à H:i') }}</p>
                                    @if($donation->approvedBy)
                                        <p class="approved-by">Par {{ $donation->approvedBy->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="details-card">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Informations du Donateur</h3>
            </div>
            <div class="card-content">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ $donation->user->initials() }}
                    </div>
                    <div class="user-details">
                        <div class="info-item">
                            <label>Nom</label>
                            <value>{{ $donation->user->name }}</value>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <value>{{ $donation->user->email }}</value>
                        </div>
                        <div class="info-item">
                            <label>Membre depuis</label>
                            <value>{{ $donation->user->created_at->format('d/m/Y') }}</value>
                        </div>
                        <div class="info-item">
                            <label>Donations totales</label>
                            <value>{{ $donation->user->donations()->count() }}</value>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Notes Card -->
        @if($donation->admin_notes || $donation->status === 'pending')
            <div class="details-card">
                <div class="card-header">
                    <h3><i class="fas fa-comment-alt"></i> Notes Administratives</h3>
                </div>
                <div class="card-content">
                    @if($donation->admin_notes)
                        <div class="admin-notes-display">
                            <div class="notes-content {{ $donation->status === 'rejected' ? 'rejection' : 'approval' }}">
                                {{ $donation->admin_notes }}
                            </div>
                        </div>
                    @else
                        <div class="no-notes">
                            <i class="fas fa-info-circle"></i>
                            <p>Aucune note administrative pour cette donation.</p>
                        </div>
                    @endif
                </div>
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
        .page-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem 0;
        }

        .quick-actions {
            display: flex;
            gap: 0.75rem;
        }

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

        .donation-details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .details-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .card-header h3 {
            margin: 0;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .book-image {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .book-image img {
            max-width: 200px;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .placeholder-image {
            width: 200px;
            height: 300px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            margin: 0 auto;
        }

        .placeholder-image i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .info-item {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: start;
        }

        .info-item label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .info-item value {
            color: #495057;
        }

        .info-item .description {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            line-height: 1.5;
            border: 1px solid #e9ecef;
        }

        .genre-tag {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .condition {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .condition-excellent { background: #d4edda; color: #155724; }
        .condition-good { background: #d1ecf1; color: #0c5460; }
        .condition-fair { background: #fff3cd; color: #856404; }
        .condition-poor { background: #f8d7da; color: #721c24; }

        .status-display {
            text-align: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 2rem;
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

        .status-timeline {
            text-align: left;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 18px;
            top: 36px;
            bottom: -24px;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item.active::after {
            background: #28a745;
        }

        .timeline-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            flex-shrink: 0;
        }

        .timeline-item.active .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-icon.success {
            background: #28a745;
            color: white;
        }

        .timeline-icon.danger {
            background: #dc3545;
            color: white;
        }

        .timeline-content h4 {
            margin: 0 0 0.25rem 0;
            color: #495057;
            font-size: 0.95rem;
        }

        .timeline-content p {
            margin: 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .approved-by {
            font-weight: 600;
            color: #495057 !important;
        }

        .user-info {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
        }

        .admin-notes-display .notes-content {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            line-height: 1.6;
            border-left: 4px solid #28a745;
        }

        .admin-notes-display .notes-content.rejection {
            border-left-color: #dc3545;
            background: #fdf2f2;
            color: #721c24;
        }

        .no-notes {
            text-align: center;
            color: #6c757d;
            padding: 2rem;
        }

        .no-notes i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
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

        @media (max-width: 1024px) {
            .donation-details-grid {
                grid-template-columns: 1fr;
            }

            .page-header-actions {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .quick-actions {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .info-item {
                grid-template-columns: 1fr;
                gap: 0.25rem;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .user-avatar {
                align-self: center;
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