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

        /* Feedbacks Section */
        .feedbacks-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
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

        /* Rating Summary */
        .rating-summary {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            margin-bottom: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-radius: 15px;
            border: 1px solid #e9ecef;
        }

        .average-rating {
            text-align: center;
            padding: 20px;
        }

        .rating-number {
            font-size: 4rem;
            font-weight: 700;
            color: #ffc107;
            line-height: 1;
            margin-bottom: 10px;
        }

        .rating-stars {
            margin-bottom: 15px;
        }

        .rating-count {
            color: var(--text-light);
            font-size: 1rem;
        }

        .rating-distribution {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .rating-label {
            width: 80px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            color: var(--text-color);
        }

        .rating-progress {
            flex: 1;
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
        }

        .rating-fill {
            height: 100%;
            background: linear-gradient(135deg, #ffc107, #ffb300);
            border-radius: 5px;
            transition: width 1s ease-in-out;
        }

        .rating-percentage {
            width: 50px;
            text-align: right;
            font-weight: 600;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Feedback Cards */
        .feedbacks-grid {
            display: grid;
            gap: 20px;
        }

        .feedback-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .feedback-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .feedback-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-info h4 {
            color: var(--primary-color);
            margin-bottom: 2px;
            font-size: 1rem;
        }

        .feedback-date {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .feedback-rating {
            display: flex;
            gap: 3px;
        }

        .star {
            color: #ffc107;
            font-size: 1.1rem;
        }

        .feedback-content {
            color: var(--text-color);
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .feedback-sentiment {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .sentiment-positive {
            background: #d4edda;
            color: #155724;
        }

        .sentiment-negative {
            background: #f8d7da;
            color: #721c24;
        }

        .sentiment-neutral {
            background: #fff3cd;
            color: #856404;
        }

        /* Empty State for Feedbacks */
        .empty-feedbacks {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-feedbacks i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--gray-light);
        }

        /* Similar Books Section */
        .similar-books {
            margin-top: 50px;
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

            .rating-summary {
                grid-template-columns: 1fr;
                gap: 30px;
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

            .rating-number {
                font-size: 3rem;
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

            .feedbacks-section {
                padding: 25px;
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

        .book-hero, .book-actions, .feedbacks-section, .similar-books {
            animation: fadeInUp 0.6s ease-out;
        }

        .feedback-card, .similar-book-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .feedback-card:nth-child(1) { animation-delay: 0.1s; }
        .feedback-card:nth-child(2) { animation-delay: 0.2s; }
        .feedback-card:nth-child(3) { animation-delay: 0.3s; }
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
                    @auth
                        @if($book->user_id != auth()->id())
                            @if($currentBorrowStatus)
                                @switch($currentBorrowStatus)
                                    @case('pending')
                                        <button class="btn-borrow" style="background: #f39c12;" disabled>
                                            <i class="fas fa-clock"></i>
                                            Demande en attente
                                        </button>
                                        @break
                                    @case('approved')
                                        <button class="btn-borrow" style="background: #3498db;" disabled>
                                            <i class="fas fa-check-circle"></i>
                                            Demande approuvée
                                        </button>
                                        @break
                                    @case('borrowed')
                                        <button class="btn-borrow" style="background: #9b59b6;" disabled>
                                            <i class="fas fa-book-reader"></i>
                                            Livre emprunté
                                        </button>
                                        @break
                                @endswitch
                            @else
                                <form action="{{ route('user.books.borrow-request', $book) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-borrow"
                                        {{ !$book->shareable || $book->archived ? 'disabled' : '' }}>
                                        <i class="fas fa-hand-holding"></i>
                                        {{ !$book->shareable || $book->archived ? 'Non disponible' : 'Emprunter ce livre' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <button class="btn-borrow" style="background: #95a5a6;" disabled>
                                <i class="fas fa-user"></i>
                                Votre propre livre
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-borrow">
                            <i class="fas fa-sign-in-alt"></i>
                            Connectez-vous pour emprunter
                        </a>
                    @endauth

                    <a href="{{ route('books.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la bibliothèque
                    </a>

                    @auth
                        <!-- Liens vers les pages d'historique -->
                        <a href="{{ route('user.books.borrowing-history') }}" class="btn-secondary">
                            <i class="fas fa-history"></i>
                            Mes emprunts
                        </a>

                        @if(auth()->user()->books()->count() > 0)
                            <a href="{{ route('user.books.lending-requests') }}" class="btn-secondary">
                                <i class="fas fa-hand-holding-heart"></i>
                                Mes prêts
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Feedbacks Section -->
            <div class="feedbacks-section">
                <div class="section-header">
                    <h2>⭐ Avis des Lecteurs</h2>
                    <p>Découvrez ce que les autres lecteurs pensent de ce livre</p>
                </div>

                @if($book->feedbacks->count() > 0)
                    <!-- Rating Summary -->
                    <div class="rating-summary">
                        <div class="average-rating">
                            <div class="rating-number">
                                {{ number_format($book->averageRating(), 1) }}
                            </div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($book->averageRating()))
                                        <i class="fas fa-star star"></i>
                                    @elseif($i - 0.5 <= $book->averageRating())
                                        <i class="fas fa-star-half-alt star"></i>
                                    @else
                                        <i class="far fa-star star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-count">
                                {{ $book->feedbacks->count() }} avis
                            </div>
                        </div>

                        <div class="rating-distribution">
                            @php
                                $ratingStats = $book->rating_stats;
                            @endphp
                            @for($i = 5; $i >= 1; $i--)
                                <div class="rating-bar">
                                    <div class="rating-label">
                                        <span>{{ $i }}</span>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="rating-progress">
                                        <div class="rating-fill" style="width: {{ $ratingStats['distribution_percent'][$i] ?? 0 }}%"></div>
                                    </div>
                                    <div class="rating-percentage">
                                        {{ $ratingStats['distribution'][$i] ?? 0 }}
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Feedbacks List -->
                    <div class="feedbacks-grid">
                        @foreach($book->feedbacks->take(5) as $feedback)
                            <div class="feedback-card">
                                <div class="feedback-header">
                                    <div class="feedback-user">
                                        <div class="user-avatar">
                                            {{ substr($feedback->user->name, 0, 1) }}
                                        </div>
                                        <div class="user-info">
                                            <h4>{{ $feedback->user->name }}</h4>
                                            <div class="feedback-date">
                                                {{ $feedback->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="feedback-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $feedback->rating)
                                                <i class="fas fa-star star"></i>
                                            @else
                                                <i class="far fa-star star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="feedback-content">
                                    {{ $feedback->comment }}
                                </div>
                                @if($feedback->sentiment)
                                    <span class="feedback-sentiment sentiment-{{ $feedback->sentiment }}">
                                        <i class="fas {{ $feedback->sentiment === 'positive' ? 'fa-smile' : ($feedback->sentiment === 'negative' ? 'fa-frown' : 'fa-meh') }}"></i>
                                        {{ $feedback->sentiment === 'positive' ? 'Positif' : ($feedback->sentiment === 'negative' ? 'Négatif' : 'Neutre') }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($book->feedbacks->count() > 5)
                        <div class="text-center mt-4">
                            <a href="{{ route('books.feedbacks', $book) }}" class="btn-secondary">
                                <i class="fas fa-comments"></i>
                                Voir tous les avis ({{ $book->feedbacks->count() }})
                            </a>
                        </div>
                    @endif

                @else
                    <div class="empty-feedbacks">
                        <i class="fas fa-comments"></i>
                        <h3>Aucun avis pour le moment</h3>
                        <p>Soyez le premier à donner votre avis sur ce livre !</p>
                        @auth
                            @if($book->user_id != auth()->id())
                                <a href="{{ route('user.books.borrowing-history') }}" class="btn-secondary mt-3">
                                    <i class="fas fa-history"></i>
                                    Empruntez ce livre pour donner votre avis
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
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
            document.querySelectorAll('.feedback-card, .similar-book-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease-out';
                observer.observe(card);
            });

            // Animation des barres de progression
            document.querySelectorAll('.rating-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
@endsection
