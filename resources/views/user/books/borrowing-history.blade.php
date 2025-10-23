@extends('layouts.user-layout')

@section('title', 'Mes Emprunts - Social Book Network')
@section('styles')
    <style>
        .borrowing-history-page {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        /* Reset et styles de base pour forcer l'application */
        .borrowing-history-page .transactions-table {
            background: white;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
            margin: 20px 0 !important;
        }

        .borrowing-history-page .table-responsive {
            border-radius: 12px !important;
            overflow: hidden !important;
        }

        .borrowing-history-page .table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            margin: 0 !important;
            min-width: 1000px;
        }

        .borrowing-history-page .table thead {
            background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%) !important;
        }
        .borrowing-history-page .table th {
            background: transparent !important;
            color: white !important;
            padding: 18px 20px !important;
            text-align: left !important;
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            border: none !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            position: relative;
        }

        .borrowing-history-page .table th:after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 60%;
            background: rgba(255,255,255,0.3);
        }

        .borrowing-history-page .table th:last-child:after {
            display: none;
        }

        .borrowing-history-page .table td {
            padding: 22px 20px !important;
            border-bottom: 1px solid #f1f3f9 !important;
            vertical-align: middle !important;
            font-size: 0.95rem !important;
            background: white !important;
            position: relative;
        }

        .borrowing-history-page .table tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative;
        }

        .borrowing-history-page .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%) !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.2) !important;
            border-radius: 8px;
        }

        .borrowing-history-page .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Styles pour les informations du livre */
        .borrowing-history-page .book-info {
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
            min-width: 250px;
        }

        .borrowing-history-page .book-cover {
            width: 60px !important;
            height: 75px !important;
            object-fit: cover !important;
            border-radius: 10px !important;
            flex-shrink: 0 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            border: 2px solid white;
        }

        .borrowing-history-page .book-cover-placeholder {
            width: 60px !important;
            height: 75px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            flex-shrink: 0 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            border: 2px solid white;
            font-size: 1.2rem;
        }

        .borrowing-history-page .book-details {
            display: flex !important;
            flex-direction: column !important;
            min-width: 0 !important;
            flex: 1;
        }

        .borrowing-history-page .book-title {
            font-weight: 800 !important;
            margin-bottom: 8px !important;
            color: #1a202c !important;
            font-size: 1.05rem !important;
            line-height: 1.3 !important;
        }

        .borrowing-history-page .book-author {
            font-size: 0.9rem !important;
            color: #718096 !important;
            font-weight: 500 !important;
        }

        /* Styles pour les autres cellules */
        .borrowing-history-page .lender-name {
            font-weight: 700 !important;
            color: #2d3748 !important;
            font-size: 1rem !important;
            min-width: 150px;
        }

        .borrowing-history-page .date-cell {
            font-weight: 600 !important;
            color: #4a5568 !important;
            font-size: 0.95rem !important;
            min-width: 120px;
        }

        /* Badges de statut améliorés */
        .borrowing-history-page .status-badge {
            padding: 12px 20px !important;
            border-radius: 30px !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            white-space: nowrap !important;
            min-width: 140px !important;
            justify-content: center !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .borrowing-history-page .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            color: #856404 !important;
            border: 2px solid #ffeaa7 !important;
        }
        .borrowing-history-page .status-approved {
            background: linear-gradient(135deg, #d1ecf1 0%, #b8e0e6 100%) !important;
            color: #0c5460 !important;
            border: 2px solid #b8e0e6 !important;
        }
        .borrowing-history-page .status-borrowed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
            color: #155724 !important;
            border: 2px solid #c3e6cb !important;
        }
        .borrowing-history-page .status-returned {
            background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%) !important;
            color: #383d41 !important;
            border: 2px solid #d6d8db !important;
        }
        .borrowing-history-page .status-completed {
            background: linear-gradient(135deg, #d1ecf1 0%, #b8e0e6 100%) !important;
            color: #0c5460 !important;
            border: 2px solid #b8e0e6 !important;
        }
        .borrowing-history-page .status-rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%) !important;
            color: #721c24 !important;
            border: 2px solid #f5c6cb !important;
        }

        /* Boutons d'action améliorés */
        .borrowing-history-page .action-buttons {
            display: flex !important;
            gap: 12px !important;
            flex-wrap: wrap !important;
            min-width: 200px;
        }

        .borrowing-history-page .action-btn {
            padding: 12px 18px !important;
            border-radius: 10px !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            border: none !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            min-width: 110px !important;
            justify-content: center !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .borrowing-history-page .btn-return {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%) !important;
            color: white !important;
        }
        .borrowing-history-page .btn-return:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%) !important;
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.4) !important;
        }

        .borrowing-history-page .btn-cancel {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%) !important;
            color: white !important;
        }
        .borrowing-history-page .btn-cancel:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%) !important;
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 8px 20px rgba(245, 101, 101, 0.4) !important;
        }

        .borrowing-history-page .btn-view {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%) !important;
            color: white !important;
        }
        .borrowing-history-page .btn-view:hover {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 8px 20px rgba(113, 128, 150, 0.4) !important;
        }

        /* État vide */
        .borrowing-history-page .empty-state {
            padding: 100px 40px !important;
            text-align: center !important;
            background: white !important;
        }

        .borrowing-history-page .empty-state i {
            font-size: 5rem !important;
            margin-bottom: 30px !important;
            color: #e2e8f0 !important;
            opacity: 0.7;
        }

        .borrowing-history-page .empty-state h5 {
            font-size: 1.8rem !important;
            color: #4a5568 !important;
            margin-bottom: 20px !important;
            font-weight: 700 !important;
        }

        .borrowing-history-page .empty-state p {
            color: #718096 !important;
            font-size: 1.2rem !important;
            margin-bottom: 30px !important;
            font-weight: 500;
        }

        .borrowing-history-page .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
            color: white !important;
            padding: 16px 32px !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 12px !important;
            transition: all 0.3s ease !important;
            border: none !important;
            font-size: 1.1rem;
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        }

        .borrowing-history-page .btn-primary:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.5) !important;
            color: white !important;
            text-decoration: none !important;
        }

        /* Pagination */
        .borrowing-history-page .pagination-container {
            padding: 30px !important;
            border-top: 1px solid #f1f3f9 !important;
            display: flex !important;
            justify-content: center !important;
            background: white !important;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .borrowing-history-page .table-responsive {
                overflow-x: auto !important;
            }
        }

        @media (max-width: 768px) {
            .borrowing-history-page {
                padding: 20px 0 !important;
            }

            .borrowing-history-page .transactions-table {
                margin: 0 !important;
                border-radius: 8px !important;
            }

            .borrowing-history-page .table th,
            .borrowing-history-page .table td {
                padding: 18px 15px !important;
            }

            .borrowing-history-page .action-buttons {
                flex-direction: column !important;
                gap: 10px !important;
            }

            .borrowing-history-page .action-btn {
                min-width: 100px !important;
                padding: 10px 15px !important;
                font-size: 0.8rem !important;
            }

            .borrowing-history-page .status-badge {
                min-width: 120px !important;
                padding: 10px 15px !important;
                font-size: 0.8rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="borrowing-history-page">
        <div class="container">
            <!-- Votre contenu existant reste inchangé -->
            <div class="page-header">
                <div>
                    <h1>📚 Mes Emprunts</h1>
                    <p>Historique de vos demandes d'emprunt</p>
                </div>
                <a href="{{ route('books.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux livres
                </a>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div class="stat-label">En attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['borrowed'] }}</div>
                    <div class="stat-label">Empruntés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['returned'] }}</div>
                    <div class="stat-label">Retournés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['completed'] }}</div>
                    <div class="stat-label">Terminés</div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="transactions-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Propriétaire</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="book-info">
                                        @if($transaction->book->book_cover)
                                            <img src="{{ Storage::disk('public')->url($transaction->book->book_cover) }}"
                                                 alt="{{ $transaction->book->title }}"
                                                 class="book-cover">
                                        @else
                                            <div class="book-cover-placeholder">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                        <div class="book-details">
                                            <div class="book-title">{{ $transaction->book->title }}</div>
                                            <div class="book-author">{{ $transaction->book->author_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="lender-name">{{ $transaction->lender->name }}</td>
                                <td class="date-cell">{{ $transaction->borrowed_date->format('d/m/Y') }}</td>
                                <td class="date-cell">{{ $transaction->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @switch($transaction->status)
                                        @case('pending')
                                            <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                            @break
                                        @case('approved')
                                            <span class="status-badge status-approved">
                                            <i class="fas fa-check-circle"></i> Approuvé
                                        </span>
                                            @break
                                        @case('borrowed')
                                            <span class="status-badge status-borrowed">
                                            <i class="fas fa-book-reader"></i> Emprunté
                                        </span>
                                            @break
                                        @case('returned')
                                            <span class="status-badge status-returned">
                                            <i class="fas fa-undo"></i> Retourné
                                        </span>
                                            @break
                                        @case('completed')
                                            <span class="status-badge status-completed">
                                            <i class="fas fa-check-double"></i> Terminé
                                        </span>
                                            @break
                                        @case('rejected')
                                            <span class="status-badge status-rejected">
                                            <i class="fas fa-times-circle"></i> Rejeté
                                        </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($transaction->status === 'pending')
                                            <form action="{{ route('user.transactions.cancel', $transaction) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-cancel"
                                                        onclick="return confirm('Annuler cette demande d\'emprunt ?')">
                                                    <i class="fas fa-times"></i> Annuler
                                                </button>
                                            </form>
                                        @endif

                                        @if($transaction->status === 'borrowed')
                                            <form action="{{ route('user.transactions.mark-returned', $transaction) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-return"
                                                        onclick="return confirm('Marquer ce livre comme retourné ?')">
                                                    <i class="fas fa-undo"></i> Retourner
                                                </button>
                                            </form>
                                        @endif
                                            @if($transaction->status === 'completed')
                                                @if(!$transaction->book->feedbacks()->where('user_id', auth()->id())->exists())
                                                    <a href="{{ route('user.feedback.create', $transaction) }}" class="action-btn"
                                                       style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white;">
                                                        <i class="fas fa-star"></i> Noter
                                                    </a>
                                                @else
                                                    <span class="action-btn" style="background: #28a745; color: white; cursor: default;">
            <i class="fas fa-check"></i> Noté
        </span>
                                                @endif
                                            @endif
                                        <a href="{{ route('books.show', $transaction->book) }}" class="action-btn btn-view">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-book-open"></i>
                                        <h5>Aucun emprunt trouvé</h5>
                                        <p class="text-muted">Vous n'avez pas encore emprunté de livres.</p>
                                        <a href="{{ route('books.index') }}" class="btn-primary">
                                            <i class="fas fa-search"></i> Explorer les livres
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="pagination-container">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
