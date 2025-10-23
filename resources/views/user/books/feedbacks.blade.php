@extends('layouts.user-layout')

@section('title', "Avis sur {$book->title} - Social Book Network")
@section('styles')
    <style>
        .feedbacks-page {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .rating-summary {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .average-rating {
            font-size: 3rem;
            font-weight: 700;
            color: #ffc107;
        }

        .rating-distribution {
            margin-top: 20px;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .rating-bar-label {
            width: 60px;
            font-weight: 600;
        }

        .rating-bar-progress {
            flex: 1;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 0 10px;
        }

        .rating-bar-fill {
            height: 100%;
            background: #ffc107;
        }

        .rating-bar-count {
            width: 40px;
            text-align: right;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .feedback-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #4361ee;
        }
    </style>
@endsection

@section('content')
    <div class="feedbacks-page">
        <div class="container">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>📖 Avis sur "{{ $book->title }}"</h1>
                    <p class="text-muted">Découvrez ce que les lecteurs pensent de ce livre</p>
                </div>
                <a href="{{ route('books.show', $book) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au livre
                </a>
            </div>

            <!-- Résumé des notes -->
            <div class="rating-summary">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="average-rating">
                            {{ number_format($ratingStats['average'], 1) }}/5
                        </div>
                        <div class="stars mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($ratingStats['average']))
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - 0.5 <= $ratingStats['average'])
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-muted">
                            {{ $ratingStats['total'] }} avis
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="rating-distribution">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="rating-bar">
                                    <div class="rating-bar-label">{{ $i }} ⭐</div>
                                    <div class="rating-bar-progress">
                                        <div class="rating-bar-fill"
                                             style="width: {{ $ratingStats['distribution_percent'][$i] ?? 0 }}%"></div>
                                    </div>
                                    <div class="rating-bar-count">
                                        {{ $ratingStats['distribution'][$i] ?? 0 }}
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des feedbacks -->
            <div class="row">
                <div class="col-12">
                    @forelse($feedbacks as $feedback)
                        <div class="feedback-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $feedback->user->name }}</h6>
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $feedback->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    {{ $feedback->created_at->diffForHumans() }}
                                    @if($feedback->sentiment)
                                        <span class="{{ $feedback->sentiment_color }} ms-2">
                                            {{ $feedback->sentiment_icon }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-0">{{ $feedback->comment }}</p>

                            <!-- Actions (modification/suppression) -->
                            @if(auth()->id() === $feedback->user_id && $feedback->created_at->diffInHours(now()) <= 24)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="btn-group btn-group-sm">
                                        @if($feedback->created_at->diffInHours(now()) <= 24)
                                            <a href="{{ route('user.feedback.edit', $feedback) }}"
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                        @endif
                                        @if($feedback->created_at->diffInHours(now()) <= 1)
                                            <form action="{{ route('user.feedback.destroy', $feedback) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('Supprimer ce feedback ?')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>Aucun avis pour le moment</h5>
                            <p class="text-muted">Soyez le premier à donner votre avis sur ce livre !</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
