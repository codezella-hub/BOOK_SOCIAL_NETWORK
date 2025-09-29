@extends('layouts.user-layout')

@section('title', 'Mes Livres - Social Book Network')
@section('styles')
    <style>
        .my-books-page {
            padding: 30px 0;
            background: #f8f9fa;
            min-height: 80vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .page-title p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .add-book-btn {
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .add-book-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .books-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.8rem;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-light);
            position: relative;
            height: fit-content;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .book-cover {
            height: 140px;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-cover img {
            transform: scale(1.05);
        }

        .book-actions-overlay {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .book-card:hover .book-actions-overlay {
            opacity: 1;
        }

        .action-btn-small {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.95);
            color: var(--primary-color);
            text-decoration: none;
            font-size: 11px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-btn-small:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .action-btn-small:disabled {
            background: rgba(255,255,255,0.6);
            color: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .action-btn-small:disabled:hover {
            background: rgba(255,255,255,0.6);
            color: var(--text-light);
            transform: none;
        }

        .book-info {
            padding: 15px;
        }

        .book-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 8px;
        }

        .book-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary-color);
            line-height: 1.3;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.4em;
        }

        .book-status {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .status-badge {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: white;
            cursor: pointer;
            border: none;
            flex-shrink: 0;
        }

        .status-badge:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .status-shareable { background: #27ae60; }
        .status-private { background: #e74c3c; }
        .status-archived { background: #f39c12; }

        .book-author {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .book-category {
            background: var(--light-color);
            color: var(--text-light);
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 8px;
        }

        .book-synopsis {
            color: var(--text-light);
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.4em;
        }

        .archived-notice {
            background: #fff3cd;
            color: #856404;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .book-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--gray-light);
        }

        .book-date {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .book-actions {
            display: flex;
            gap: 6px;
        }

        .btn-action {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 3px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .btn-edit:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-delete {
            background: #fee;
            color: #e74c3c;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        .btn-details {
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 3px;
            transition: all 0.3s ease;
        }

        .btn-details:hover {
            background: var(--secondary-color);
            color: white;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #27ae60;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #e74c3c;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--gray-light);
        }

        .empty-state h3 {
            margin-bottom: 8px;
            color: var(--text-color);
            font-size: 1.2rem;
        }

        .empty-state p {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid var(--gray-light);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .my-books-page {
                padding: 20px 0;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
                margin-bottom: 25px;
            }

            .page-title h1 {
                font-size: 1.6rem;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 15px;
            }

            .books-stats {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-number {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            .books-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .book-cover {
                height: 120px;
            }

            .book-info {
                padding: 12px;
            }

            .books-stats {
                grid-template-columns: 1fr;
            }

            .book-actions {
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            .btn-action, .btn-details {
                font-size: 0.7rem;
                padding: 4px 8px;
            }
        }

        @media (max-width: 360px) {
            .books-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="my-books-page">
        <div class="container">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1>📖 Mes Livres</h1>
                    <p>Gérez votre collection personnelle</p>
                </div>
                <a href="{{ route('user.books.create') }}" class="add-book-btn">
                    <i class="fas fa-plus"></i>
                    Ajouter un livre
                </a>
            </div>

            <!-- Books Stats -->
            <div class="books-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->books()->count() }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->books()->where('shareable', true)->where('archived', false)->count() }}</div>
                    <div class="stat-label">Partagés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ auth()->user()->books()->where('archived', true)->count() }}</div>
                    <div class="stat-label">Archivés</div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="books-grid">
                @forelse($books as $book)
                    <div class="book-card">
                        <div class="book-cover">
                            @if($book->book_cover)
                                <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                     alt="{{ $book->title }}"
                                     onerror="this.src='https://via.placeholder.com/300x200/667eea/ffffff?text=Couverture'">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                            <div class="book-actions-overlay">
                                <!-- Bouton Archive -->
                                <form action="{{ route('user.books.toggle-archive', $book) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn-small" title="{{ $book->archived ? 'Désarchiver' : 'Archiver' }}">
                                        <i class="fas {{ $book->archived ? 'fa-box-open' : 'fa-archive' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="book-info">
                            <div class="book-header">
                                <h3 class="book-title">{{ $book->title }}</h3>
                                <div class="book-status">
                                    @if($book->archived)
                                        <span class="status-badge status-archived" title="Archivé - Non partageable">
                                            <i class="fas fa-archive"></i>
                                        </span>
                                    @endif

                                    <!-- Bouton Share - Désactivé si archivé -->
                                    <form action="{{ route('user.books.toggle-shareable', $book) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="status-badge {{ $book->shareable ? 'status-shareable' : 'status-private' }}"
                                                title="{{ $book->archived ? 'Archivé - Non partageable' : ($book->shareable ? 'Partageable - Cliquez pour rendre privé' : 'Privé - Cliquez pour partager') }}"
                                            {{ $book->archived ? 'disabled' : '' }}>
                                            <i class="fas {{ $book->shareable ? 'fa-share-alt' : 'fa-lock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="book-author">
                                <i class="fas fa-user-edit"></i>
                                {{ $book->author_name }}
                            </p>
                            <span class="book-category">{{ $book->category->name }}</span>

                            @if($book->archived)
                                <div class="archived-notice">
                                    <i class="fas fa-info-circle"></i> Archivé
                                </div>
                            @endif

                            @if($book->synopsis)
                                <p class="book-synopsis">{{ Str::limit($book->synopsis, 70) }}</p>
                            @endif

                            <div class="book-footer">
                                <span class="book-date">{{ $book->created_at->format('d/m/Y') }}</span>
                                <div class="book-actions">
                                    <a href="{{ route('books.show', $book) }}" class="btn-details" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('user.books.edit', $book) }}" class="btn-action btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('user.books.destroy', $book) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h3>Vous n'avez pas encore de livres</h3>
                        <p>Commencez à constituer votre bibliothèque personnelle</p>
                        <a href="{{ route('user.books.create') }}" class="add-book-btn" style="display: inline-flex; margin-top: 15px;">
                            <i class="fas fa-plus"></i>
                            Ajouter mon premier livre
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($books->hasPages())
                <div class="pagination">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Ajouter une confirmation pour l'archivage
        document.addEventListener('DOMContentLoaded', function() {
            const archiveForms = document.querySelectorAll('form[action*="toggle-archive"]');

            archiveForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const isArchived = button.title.includes('Désarchiver');

                    if (!confirm(`Êtes-vous sûr de vouloir ${isArchived ? 'désarchiver' : 'archiver'} ce livre ?`)) {
                        e.preventDefault();
                    }
                });
            });

            // Ajouter une confirmation pour le partage
            const shareForms = document.querySelectorAll('form[action*="toggle-shareable"]');

            shareForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const isShareable = button.title.includes('rendre privé');

                    if (!confirm(`Êtes-vous sûr de vouloir ${isShareable ? 'rendre ce livre privé' : 'partager ce livre'} ?`)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
