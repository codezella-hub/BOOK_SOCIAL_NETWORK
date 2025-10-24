@extends('layouts.user-layout')

@section('title', 'Donner un Feedback - Social Book Network')
@section('styles')
    <style>
        .feedback-page {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .feedback-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        .book-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .book-cover {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .book-cover-placeholder {
            width: 80px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-size: 1.5rem;
        }

        .rating-stars {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            justify-content: center;
        }

        .star {
            font-size: 2.5rem;
            cursor: pointer;
            color: #e0e0e0;
            transition: all 0.3s ease;
        }

        .star:hover,
        .star.active {
            color: #ffc107;
            transform: scale(1.1);
        }

        .char-count {
            text-align: right;
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="feedback-page">
        <div class="container">
            <div class="feedback-card">
                <div class="text-center mb-4">
                    <h1>⭐ Donner votre avis</h1>
                    <p class="text-muted">Partagez votre expérience avec ce livre</p>
                </div>

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
                    <div>
                        <h4 class="mb-2">{{ $transaction->book->title }}</h4>
                        <p class="text-muted mb-1">de {{ $transaction->book->author_name }}</p>
                        <p class="text-muted mb-0">
                            Emprunté du {{ $transaction->borrowed_date->format('d/m/Y') }}
                            au {{ $transaction->returned_date->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('user.feedback.store', $transaction) }}" method="POST">
                    @csrf

                    <!-- Note -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Note *</label>
                        <div class="rating-stars" id="ratingStars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-rating="{{ $i }}">
                                    <i class="far fa-star"></i>
                                </span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0" required>
                        @error('rating')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-4">
                        <label for="comment" class="form-label fw-bold">Commentaire *</label>
                        <textarea name="comment" id="comment" class="form-control" rows="6"
                                  placeholder="Partagez votre expérience de lecture... (minimum 10 caractères)"
                                  minlength="10" maxlength="1000" required>{{ old('comment') }}</textarea>
                        <div class="char-count">
                            <span id="charCount">0</span>/1000 caractères
                        </div>
                        @error('comment')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('user.books.borrowing-history') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Envoyer le feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Gestion des étoiles de notation
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        const commentTextarea = document.getElementById('comment');
        const charCount = document.getElementById('charCount');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;

                // Mettre à jour l'affichage des étoiles
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                        s.innerHTML = '<i class="fas fa-star"></i>';
                    } else {
                        s.classList.remove('active');
                        s.innerHTML = '<i class="far fa-star"></i>';
                    }
                });
            });

            // Effet de survol
            star.addEventListener('mouseenter', function() {
                const rating = this.getAttribute('data-rating');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.innerHTML = '<i class="fas fa-star"></i>';
                        s.style.color = '#ffc107';
                        s.style.opacity = '0.7';
                        s.style.transform = 'scale(1.05)';
                        s.style.transition = 'all 0.2s ease';
                    }
                    s.style.cursor = 'pointer';
                });

                // Restaurer l'état initial quand la souris quitte
                star.addEventListener('mouseleave', function() {
                    const currentRating = ratingInput.value;
                    stars.forEach((s, index) => {
                        if (index >= currentRating) {
                            s.innerHTML = '<i class="far fa-star"></i>';
                            s.style.color = '#e0e0e0';
                            s.style.opacity = '1';
                            s.style.transform = 'scale(1)';
                        }
                    });
                });
            });
        });

        // Compteur de caractères
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;

            if (length < 10) {
                charCount.style.color = '#dc3545';
            } else if (length > 900) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#28a745';
            }
        });

        // Validation avant envoi
        document.querySelector('form').addEventListener('submit', function(e) {
            if (ratingInput.value === '0') {
                e.preventDefault();
                alert('Veuillez donner une note en cliquant sur les étoiles.');
                return;
            }

            if (commentTextarea.value.length < 10) {
                e.preventDefault();
                alert('Veuillez écrire un commentaire d\'au moins 10 caractères.');
                return;
            }
        });
    </script>
@endsection
