@extends('layouts.user-layout')

@section('title', $book->title . ' - Social Book Network')
@section('styles')
    <style>
        .book-detail-page {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 80vh;
        }

        .book-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .book-main {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        /* Book Cover Section */
        .book-cover-section {
            text-align: center;
        }

        .book-cover-large {
            width: 100%;
            max-width: 300px;
            border-radius: 12px;
            box-shadow: var(--shadow-hover);
            margin-bottom: 20px;
        }

        .book-actions-main {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-main {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--light-color);
            color: var(--text-color);
            border: 1px solid var(--gray-light);
        }

        .btn-secondary:hover {
            background: var(--gray-light);
        }

        /* Book Info Section */
        .book-info-section {
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .book-header {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .book-title {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .book-author {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .book-status-badges {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-shareable { background: #e8f5e8; color: #27ae60; }
        .status-private { background: #ffebee; color: #e74c3c; }
        .status-archived { background: #fff3e0; color: #f39c12; }

        .book-details {
            margin-bottom: 30px;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .synopsis-text {
            line-height: 1.6;
            color: var(--text-color);
            font-size: 1rem;
        }

        .isbn-code {
            font-family: 'Courier New', monospace;
            background: var(--light-color);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        /* Owner Info */
        .owner-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: var(--light-color);
            border-radius: 8px;
            margin-top: 25px;
        }

        .owner-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .owner-details h4 {
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .owner-details p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Related Books */
        .related-books {
            margin-top: 50px;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 25px;
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-book-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .related-book-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .related-book-cover {
            height: 150px;
            overflow: hidden;
        }

        .related-book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-book-info {
            padding: 15px;
        }

        .related-book-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .related-book-author {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .book-main {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .book-cover-section {
                text-align: center;
            }

            .book-cover-large {
                max-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .book-title {
                font-size: 1.8rem;
            }

            .book-author {
                font-size: 1.1rem;
            }

            .book-meta {
                flex-direction: column;
                gap: 10px;
            }

            .related-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .book-info-section {
                padding: 20px;
            }

            .book-actions-main {
                flex-direction: column;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }
        }

        .book-success-indicators {
            border-top: 1px solid #f0f0f0;
            padding-top: 8px;
        }

        .related-book-card {
            position: relative;
        }

        .related-book-card::before {
            content: '🔥 Populaire';
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
    <div class="book-detail-page">
        <div class="container">
            <div class="book-container">
                <!-- Main Book Section -->
                <div class="book-main">
                    <!-- Book Cover -->
                    <div class="book-cover-section">
                        <div class="book-cover-large">
                            @if($book->book_cover)
                                <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                     alt="{{ $book->title }}"
                                     class="book-cover-large"
                                     onerror="this.src='https://via.placeholder.com/300x400/667eea/ffffff?text=Couverture'">
                            @else
                                <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Actions for book owner -->
                        @if(auth()->id() == $book->user_id)
                            <div class="book-actions-main">
                                <a href="{{ route('user.books.edit', $book) }}" class="btn-main btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Modifier le livre
                                </a>
                                <a href="{{ route('user.books.my-books') }}" class="btn-main btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    Mes livres
                                </a>
                            </div>
                        @else
                            <div class="book-actions-main">
                                <a href="{{ route('books.index') }}" class="btn-main btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    Retour aux livres
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Book Information -->
                    <div class="book-info-section">
                        <div class="book-header">
                            <h1 class="book-title">{{ $book->title }}</h1>
                            <div class="book-author">
                                <i class="fas fa-user-edit"></i>
                                {{ $book->author_name }}
                            </div>
                            <div class="book-meta">
                                <div class="meta-item">
                                    <i class="fas fa-tags"></i>
                                    <span>{{ $book->category->name }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>Ajouté le {{ $book->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-eye"></i>
                                    <span>Partagé par {{ $book->user->name }}</span>
                                </div>
                            </div>
                            <div class="book-status-badges">
                                @if($book->archived)
                                    <span class="status-badge status-archived">
                                    <i class="fas fa-archive"></i>
                                    Archivé
                                </span>
                                @endif
                                <span class="status-badge {{ $book->shareable ? 'status-shareable' : 'status-private' }}">
                                <i class="fas {{ $book->shareable ? 'fa-share-alt' : 'fa-lock' }}"></i>
                                {{ $book->shareable ? 'Partageable' : 'Privé' }}
                            </span>
                            </div>
                        </div>

                        <div class="book-details">
                            <!-- Synopsis -->
                            @if($book->synopsis)
                                <div class="detail-section">
                                    <h3 class="detail-title">
                                        <i class="fas fa-file-alt"></i>
                                        Synopsis
                                    </h3>
                                    <p class="synopsis-text">{{ $book->synopsis }}</p>
                                </div>
                            @endif

                            <!-- ISBN -->
                            <div class="detail-section">
                                <h3 class="detail-title">
                                    <i class="fas fa-barcode"></i>
                                    ISBN
                                </h3>
                                <code class="isbn-code">{{ $book->isbn }}</code>
                            </div>
                        </div>

                        <!-- Owner Information -->
                        <div class="owner-info">
                            <div class="owner-avatar">
                                {{ substr($book->user->name, 0, 1) }}
                            </div>
                            <div class="owner-details">
                                <h4>Partagé par {{ $book->user->name }}</h4>
                                <p>Membre depuis {{ $book->user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Books -->
                <!-- Related Books -->
                @if($relatedBooks->count() > 0)
                    <div class="related-books">
                        <h2 class="section-title">Livres populaires similaires</h2>
                        <div class="related-grid">
                            @foreach($relatedBooks as $relatedBook)
                                <a href="{{ route('books.show', $relatedBook) }}" class="related-book-card">
                                    <div class="related-book-cover">
                                        @if($relatedBook->book_cover)
                                            <img src="{{ Storage::disk('public')->url($relatedBook->book_cover) }}"
                                                 alt="{{ $relatedBook->title }}"
                                                 onerror="this.src='https://via.placeholder.com/250x150/667eea/ffffff?text=Couverture'">
                                        @else
                                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="related-book-info">
                                        <h4 class="related-book-title">{{ Str::limit($relatedBook->title, 40) }}</h4>
                                        <p class="related-book-author">{{ $relatedBook->author_name }}</p>

                                        <!-- Indicateurs de succès -->
                                        <div class="book-success-indicators" style="margin-top: 8px; font-size: 0.75rem;">
                                            <!-- Note moyenne -->
                                            @if($relatedBook->avg_rating)
                                                <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 2px;">
                                    <span style="color: #ffc107;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= round($relatedBook->avg_rating) ? '' : '-o' }}"></i>
                                        @endfor
                                    </span>
                                                    <span style="color: #6c757d;">({{ number_format($relatedBook->avg_rating, 1) }})</span>
                                                </div>
                                            @endif

                                            <!-- Nombre de transactions -->
                                            @if($relatedBook->transactions_count > 0)
                                                <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 2px;">
                                                    <i class="fas fa-exchange-alt" style="color: #28a745;"></i>
                                                    <span style="color: #6c757d;">{{ $relatedBook->transactions_count }} emprunts</span>
                                                </div>
                                            @endif

                                            <!-- Sentiment positif -->
                                            @if($relatedBook->positive_feedbacks_count > 0)
                                                <div style="display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-smile" style="color: #28a745;"></i>
                                                    <span style="color: #6c757d;">{{ $relatedBook->positive_feedbacks_count }} avis positifs</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
