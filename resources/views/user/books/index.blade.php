@extends('layouts.user-layout')

@section('title', 'Bibliothèque Complète - Tous les livres - Social Book Network')
@section('styles')
    <style>
        .books-page {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .books-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Filters Sidebar */
        .filters-sidebar {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .filter-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-title i {
            color: var(--text-light);
        }

        .category-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .category-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .category-item:hover {
            background: var(--light-color);
        }

        .category-item.active {
            background: var(--primary-color);
            color: white;
        }

        .category-count {
            margin-left: auto;
            font-size: 0.8rem;
            color: var(--text-light);
            background: var(--light-color);
            padding: 2px 8px;
            border-radius: 12px;
        }

        .category-item.active .category-count {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .filter-option:hover {
            background: var(--light-color);
        }

        .filter-option.active {
            background: var(--primary-color);
            color: white;
        }

        /* Books Grid */
        .books-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .search-form {
            display: flex;
            gap: 15px;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        /* Compact Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-light);
            display: flex;
            flex-direction: column;
            height: 100%;
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

        .book-status {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 4px;
        }

        .status-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .status-shareable { background: #27ae60; }
        .status-private { background: #e74c3c; }
        .status-archived {
            background: #f39c12;
            position: absolute;
            top: 8px;
            left: 8px;
        }

        .book-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .book-category {
            background: var(--light-color);
            color: var(--text-light);
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 8px;
            align-self: flex-start;
        }

        .book-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 6px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.6em;
        }

        .book-author {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 0.7rem;
            color: var(--text-light);
            background: var(--light-color);
            padding: 2px 6px;
            border-radius: 8px;
        }

        .meta-item i {
            font-size: 0.65rem;
        }

        .book-owner {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--gray-light);
        }

        .owner-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: 600;
        }

        .owner-name {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .book-actions {
            display: flex;
            gap: 6px;
        }

        .btn-details {
            flex: 1;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
        }

        .btn-details:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        /* Call to Action for non-authenticated users */
        .auth-cta {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
            margin-top: 30px;
        }

        .auth-cta h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .auth-cta p {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-auth {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-register {
            background: var(--primary-color);
            color: white;
        }

        .btn-register:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-login {
            background: var(--light-color);
            color: var(--text-color);
            border: 1px solid var(--gray-light);
        }

        .btn-login:hover {
            background: var(--gray-light);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination a, .pagination span {
            padding: 10px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--gray-light);
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--text-color);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .books-container {
                grid-template-columns: 1fr;
            }

            .filters-sidebar {
                position: static;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 15px;
            }

            .search-form {
                flex-direction: column;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .auth-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-auth {
                width: 100%;
                justify-content: center;
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

            .books-page {
                padding: 20px 0;
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
    <div class="books-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>📚 Bibliothèque Partagée</h1>
                <p>Explorez tous les livres partageables de notre plateforme - Collection accessible de notre communauté</p>
            </div>

            <!-- Books Container -->
            <div class="books-container">
                <!-- Filters Sidebar -->
                <aside class="filters-sidebar">
                    <!-- Categories Filter -->
                    <div class="filter-section">
                        <h3 class="filter-title">
                            <i class="fas fa-tags"></i>
                            Catégories
                        </h3>
                        <div class="category-list">
                            <a href="{{ route('books.index', request()->except('category')) }}"
                               class="category-item {{ !request('category') ? 'active' : '' }}">
                                <i class="fas fa-layer-group"></i>
                                <span>Toutes les catégories</span>
                                <span class="category-count">{{ $totalBooks }}</span>
                            </a>
                            @foreach($categories as $category)
                                @php
                                    $categoryBookCount = $category->books()->where('shareable', true)->where('archived', false)->count();
                                @endphp
                                @if($categoryBookCount > 0)
                                    <a href="{{ route('books.index', array_merge(request()->except('category'), ['category' => $category->id])) }}"
                                       class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                                        <i class="fas fa-book"></i>
                                        <span>{{ $category->name }}</span>
                                        <span class="category-count">{{ $categoryBookCount }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="filter-section">
                        <h3 class="filter-title">
                            <i class="fas fa-sort"></i>
                            Trier par
                        </h3>
                        <div class="filter-options">
                            <a href="{{ route('books.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                               class="filter-option {{ request('sort', 'latest') == 'latest' ? 'active' : '' }}">
                                <i class="fas fa-clock"></i>
                                <span>Plus récents</span>
                            </a>
                            <a href="{{ route('books.index', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}"
                               class="filter-option {{ request('sort') == 'oldest' ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <span>Plus anciens</span>
                            </a>
                            <a href="{{ route('books.index', array_merge(request()->except('sort'), ['sort' => 'title_asc'])) }}"
                               class="filter-option {{ request('sort') == 'title_asc' ? 'active' : '' }}">
                                <i class="fas fa-sort-alpha-down"></i>
                                <span>Titre A-Z</span>
                            </a>
                            <a href="{{ route('books.index', array_merge(request()->except('sort'), ['sort' => 'title_desc'])) }}"
                               class="filter-option {{ request('sort') == 'title_desc' ? 'active' : '' }}">
                                <i class="fas fa-sort-alpha-up"></i>
                                <span>Titre Z-A</span>
                            </a>
                            <a href="{{ route('books.index', array_merge(request()->except('sort'), ['sort' => 'author_asc'])) }}"
                               class="filter-option {{ request('sort') == 'author_asc' ? 'active' : '' }}">
                                <i class="fas fa-user-edit"></i>
                                <span>Auteur A-Z</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="filter-section">
                        <h3 class="filter-title">
                            <i class="fas fa-info-circle"></i>
                            À propos
                        </h3>
                        <div style="color: var(--text-light); font-size: 0.85rem; line-height: 1.5;">
                            <p>Cette page affiche uniquement les <strong>livres partageables</strong> de la plateforme.</p>
                            @auth
                                <p style="margin-top: 10px;">💡 <strong>Vos livres :</strong> <a href="{{ route('user.books.my-books') }}" style="color: var(--primary-color);">Gérez votre collection personnelle</a></p>
                            @else
                                <p style="margin-top: 10px;">🔒 <strong>Connectez-vous</strong> pour voir vos livres privés et en ajouter de nouveaux.</p>
                            @endauth
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="books-content">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <form method="GET" action="{{ route('books.index') }}" class="search-form">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Rechercher un livre par titre, auteur, ISBN ou description..."
                                   class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                                Rechercher
                            </button>
                        </form>
                    </div>

                    <!-- Books Grid -->
                    @if($books->count() > 0)
                        <div class="books-grid">
                            @foreach($books as $book)
                                <div class="book-card">
                                    <div class="book-cover">
                                        @if($book->book_cover)
                                            <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                                 alt="{{ $book->title }}"
                                                 onerror="this.src='https://via.placeholder.com/300x400/667eea/ffffff?text=Couverture'">
                                        @else
                                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                        <div class="book-status">
                                            @if($book->archived)
                                                <span class="status-badge status-archived" title="Livre archivé">
                                                    <i class="fas fa-archive"></i>
                                                </span>
                                            @endif
                                            <!-- Only show shareable badge since all books are shareable -->
                                            <span class="status-badge status-shareable" title="Livre partageable">
                                                <i class="fas fa-share-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <span class="book-category">{{ $book->category->name }}</span>
                                        <h3 class="book-title">{{ $book->title }}</h3>
                                        <p class="book-author">
                                            <i class="fas fa-user-edit"></i>
                                            {{ $book->author_name }}
                                        </p>

                                        <!-- Book Metadata -->
                                        <div class="book-meta">
                                            @if($book->publication_year)
                                                <div class="meta-item">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span>{{ $book->publication_year }}</span>
                                                </div>
                                            @endif
                                            @if($book->pages_count)
                                                <div class="meta-item">
                                                    <i class="fas fa-file-alt"></i>
                                                    <span>{{ $book->pages_count }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="book-owner">
                                            <div class="owner-avatar">
                                                {{ substr($book->user->name, 0, 1) }}
                                            </div>
                                            <span class="owner-name">{{ $book->user->name }}</span>
                                        </div>
                                        <div class="book-actions">
                                            <a href="{{ route('books.showDetailsPublic', $book) }}" class="btn-details">
                                                <i class="fas fa-eye"></i>
                                                Détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($books->hasPages())
                            <div class="pagination">
                                {{ $books->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book-open"></i>
                            <h3>Aucun livre trouvé</h3>
                            <p>Aucun livre partageable ne correspond à vos critères de recherche.</p>
                            @if(request()->anyFilled(['search', 'category']))
                                <a href="{{ route('books.index') }}" class="btn" style="margin-top: 15px;">
                                    <i class="fas fa-refresh"></i> Réinitialiser les filtres
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Call to Action for Non-Authenticated Users -->
                    @guest
                        <div class="auth-cta">
                            <h3>🎯 Rejoignez notre communauté de lecteurs !</h3>
                            <p>Créez votre compte pour ajouter vos livres préférés, créer votre bibliothèque personnelle et échanger avec d'autres passionnés.</p>
                            <div class="auth-buttons">
                                <a href="{{ route('register') }}" class="btn-auth btn-register">
                                    <i class="fas fa-user-plus"></i>
                                    Créer un compte
                                </a>
                                <a href="{{ route('login') }}" class="btn-auth btn-login">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Se connecter
                                </a>
                            </div>
                        </div>
                    @endguest
                </main>
            </div>
        </div>
    </div>
@endsection
