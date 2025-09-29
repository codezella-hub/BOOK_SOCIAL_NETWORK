@extends('layouts.user-layout')

@section('title', $book->title . ' - Social Book Network')
@section('styles')
    <style>
        .book-details-page {
            padding: 40px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .book-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Book Hero Section */
        .book-hero {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            margin-bottom: 50px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .book-hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 0 0 0 100px;
            opacity: 0.05;
        }

        .book-cover-large {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            height: 400px;
        }

        .book-cover-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-cover-large:hover img {
            transform: scale(1.05);
        }

        .book-status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.95);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .status-available { color: #27ae60; }
        .status-private { color: #e74c3c; }
        .status-archived { color: #f39c12; }

        .book-info {
            padding: 20px 0;
        }

        .book-category {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .book-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .book-author {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--light-color);
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .meta-item i {
            color: var(--primary-color);
        }

        .book-synopsis {
            background: var(--light-color);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            line-height: 1.6;
            color: var(--text-color);
            border-left: 4px solid var(--primary-color);
        }

        .book-synopsis h4 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        /* Book Actions */
        .book-actions {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-borrow {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-borrow:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
        }

        .btn-borrow:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-borrow:disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: var(--light-color);
            color: var(--text-color);
            border: 2px solid var(--gray-light);
            padding: 13px 25px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Owner Info */
        .owner-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--light-color);
            padding: 20px;
            border-radius: 15px;
            margin-top: 20px;
        }

        .owner-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .owner-details h4 {
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .owner-details p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Similar Books Section */
        .similar-books {
            margin-top: 50px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .section-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .similar-book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .similar-book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .similar-book-cover {
            height: 180px;
            position: relative;
            overflow: hidden;
        }

        .similar-book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .similar-book-card:hover .similar-book-cover img {
            transform: scale(1.1);
        }

        .similar-book-info {
            padding: 20px;
        }

        .similar-book-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .similar-book-author {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .similar-book-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--gray-light);
        }

        .btn-view {
            background: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Empty State for Similar Books */
        .empty-similar {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
            grid-column: 1 / -1;
        }

        .empty-similar i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--gray-light);
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .book-hero {
                grid-template-columns: 250px 1fr;
                gap: 30px;
            }

            .book-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .book-hero {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .book-cover-large {
                max-width: 300px;
                margin: 0 auto;
            }

            .book-meta {
                justify-content: center;
            }

            .action-buttons {
                justify-content: center;
            }

            .owner-info {
                justify-content: center;
                text-align: center;
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .book-details-page {
                padding: 20px 0;
            }

            .book-hero {
                padding: 20px;
                margin-bottom: 30px;
            }

            .book-title {
                font-size: 1.5rem;
            }

            .book-author {
                font-size: 1.1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-borrow, .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .similar-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .book-hero, .book-actions, .similar-books {
            animation: fadeInUp 0.6s ease-out;
        }

        .similar-book-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .similar-book-card:nth-child(1) { animation-delay: 0.1s; }
        .similar-book-card:nth-child(2) { animation-delay: 0.2s; }
        .similar-book-card:nth-child(3) { animation-delay: 0.3s; }
        .similar-book-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
@endsection

@section('content')
    <div class="book-details-page">
        <div class="container book-container">
            <!-- Book Hero Section -->
            <div class="book-hero">
                <div class="book-cover-large">
                    @if($book->book_cover)
                        <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                             alt="{{ $book->title }}"
                             onerror="this.src='https://via.placeholder.com/400x600/667eea/ffffff?text=Couverture'">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif

                    <div class="book-status-badge {{ $book->archived ? 'status-archived' : ($book->shareable ? 'status-available' : 'status-private') }}">
                        <i class="fas {{ $book->archived ? 'fa-archive' : ($book->shareable ? 'fa-share-alt' : 'fa-lock') }}"></i>
                        {{ $book->archived ? 'Archivé' : ($book->shareable ? 'Disponible' : 'Privé') }}
                    </div>
                </div>

                <div class="book-info">
                    <span class="book-category">{{ $book->category->name }}</span>
                    <h1 class="book-title">{{ $book->title }}</h1>
                    <p class="book-author">
                        <i class="fas fa-user-edit"></i>
                        {{ $book->author_name }}
                    </p>

                    <div class="book-meta">
                        @if($book->isbn)
                            <div class="meta-item">
                                <i class="fas fa-barcode"></i>
                                <span>ISBN: {{ $book->isbn }}</span>
                            </div>
                        @endif
                        @if($book->publication_year)
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Année: {{ $book->publication_year }}</span>
                            </div>
                        @endif
                        @if($book->pages_count)
                            <div class="meta-item">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $book->pages_count }} pages</span>
                            </div>
                        @endif
                    </div>

                    @if($book->synopsis)
                        <div class="book-synopsis">
                            <h4>📖 Synopsis</h4>
                            <p>{{ $book->synopsis }}</p>
                        </div>
                    @endif

                    <!-- Owner Information -->
                    <div class="owner-info">
                        <div class="owner-avatar">
                            {{ substr($book->user->name, 0, 1) }}
                        </div>
                        <div class="owner-details">
                            <h4>Propriétaire du livre</h4>
                            <p>{{ $book->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Actions -->
            <div class="book-actions">
                <div class="action-buttons">
                    <button class="btn-borrow"
                            {{ !$book->shareable || $book->archived ? 'disabled' : '' }}
                            onclick="borrowBook({{ $book->id }})">
                        <i class="fas fa-hand-holding"></i>
                        {{ !$book->shareable || $book->archived ? 'Non disponible' : 'Emprunter ce livre' }}
                    </button>

                    <a href="{{ route('books.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la bibliothèque
                    </a>


                </div>
            </div>

            <!-- Similar Books Section -->
            @if($relatedBooks->count() > 0)
                <div class="similar-books">
                    <div class="section-header">
                        <h2>📚 Livres Similaires</h2>
                        <p>Découvrez d'autres livres de la même catégorie</p>
                    </div>

                    <div class="similar-grid">
                        @foreach($relatedBooks as $relatedBook)
                            <div class="similar-book-card">
                                <div class="similar-book-cover">
                                    @if($relatedBook->book_cover)
                                        <img src="{{ Storage::disk('public')->url($relatedBook->book_cover) }}"
                                             alt="{{ $relatedBook->title }}"
                                             onerror="this.src='https://via.placeholder.com/300x200/667eea/ffffff?text=Couverture'">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="similar-book-info">
                                    <span style="background: var(--light-color); color: var(--text-light); padding: 3px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 500; display: inline-block; margin-bottom: 8px;">
                                        {{ $relatedBook->category->name }}
                                    </span>
                                    <h3 class="similar-book-title">{{ $relatedBook->title }}</h3>
                                    <p class="similar-book-author">
                                        <i class="fas fa-user-edit"></i>
                                        {{ $relatedBook->author_name }}
                                    </p>
                                    <div class="similar-book-actions">
                                        <span style="font-size: 0.8rem; color: var(--text-light);">
                                            Par {{ $relatedBook->user->name }}
                                        </span>
                                        <a href="{{ route('books.show', $relatedBook) }}" class="btn-view">
                                            <i class="fas fa-eye"></i>
                                            Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="similar-books">
                    <div class="section-header">
                        <h2>📚 Livres Similaires</h2>
                        <p>Découvrez d'autres livres qui pourraient vous intéresser</p>
                    </div>
                    <div class="empty-similar">
                        <i class="fas fa-book-open"></i>
                        <h3>Aucun livre similaire trouvé</h3>
                        <p>Explorez d'autres catégories pour découvrir plus de livres</p>
                        <a href="{{ route('books.index') }}" class="btn-secondary" style="margin-top: 15px;">
                            <i class="fas fa-search"></i>
                            Explorer la bibliothèque
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        import Swal from "sweetalert2";

        function borrowBook(bookId) {
            // Simulation de la fonction d'emprunt
            Swal.fire({
                title: 'Emprunter ce livre ?',
                text: 'Voulez-vous vraiment emprunter ce livre ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#e74c3c',
                confirmButtonText: 'Oui, emprunter',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ici vous pouvez ajouter la logique d'emprunt
                    Swal.fire(
                        'Succès !',
                        'Votre demande d\'emprunt a été envoyée.',
                        'success'
                    );
                }
            });
        }

        // Animation au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observer les éléments à animer
            document.querySelectorAll('.similar-book-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease-out';
                observer.observe(card);
            });
        });
    </script>
@endsection
