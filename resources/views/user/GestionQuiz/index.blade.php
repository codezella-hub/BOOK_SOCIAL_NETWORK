@extends('layouts.user-layout')

@section('title', 'Quiz disponibles')

@section('styles')
<style>
.quiz-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}
.page-header {
    text-align: center;
    margin-bottom: 50px;
}
.page-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}
.page-header p {
    font-size: 1.2em;
    color: #7f8c8d;
}
.quiz-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}
.quiz-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}
.quiz-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.quiz-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    color: white;
}
.quiz-title {
    font-size: 1.5em;
    margin-bottom: 10px;
    font-weight: bold;
}
.quiz-book {
    font-size: 0.9em;
    opacity: 0.9;
}
.quiz-body {
    padding: 20px;
}
.quiz-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
    min-height: 60px;
}
.quiz-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}
.stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}
.stat-value {
    font-size: 1.2em;
    font-weight: bold;
    color: #667eea;
}
.stat-label {
    font-size: 0.8em;
    color: #999;
    text-transform: uppercase;
    margin-top: 5px;
}
.difficulty-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: bold;
    text-transform: uppercase;
    color: white;
}
.difficulty-beginner { background: #27ae60; }
.difficulty-intermediate { background: #f39c12; }
.difficulty-advanced { background: #e74c3c; }

.quiz-actions {
    display: flex;
    gap: 10px;
    justify-content: space-between;
}
.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
    flex: 1;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-primary {
    background: #667eea;
    color: white;
}
.btn-primary:hover {
    background: #5a67d8;
    text-decoration: none;
    color: white;
}
.btn-success {
    background: #48bb78;
    color: white;
}
.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}
.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.user-progress {
    padding: 10px;
    background: #f0f9ff;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 0.9em;
}
.progress-bar {
    height: 8px;
    background: #e0e7ff;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 5px;
}
.progress-fill {
    height: 100%;
    background: #667eea;
    transition: width 0.3s;
}
.no-quiz {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.no-quiz i {
    font-size: 4em;
    color: #cbd5e0;
    margin-bottom: 20px;
}
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.alert-error {
    background: #fed7d7;
    color: #9b2c2c;
    border: 1px solid #fc8181;
}
.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border: 1px solid #9ae6b4;
}
</style>
@endsection

@section('content')
<div class="quiz-container">
    <div class="page-header">
        <h1><i class="fas fa-question-circle"></i> Quiz disponibles</h1>
        <p>Testez vos connaissances sur vos livres préférés 📚</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($quizzes->count() > 0)
        <div class="quiz-grid">
            @foreach($quizzes as $quiz)
                <div class="quiz-card">
                    <div class="quiz-header">
                        <h2 class="quiz-title">{{ $quiz->title }}</h2>
                        <p class="quiz-book">📖 {{ $quiz->book->title ?? 'Livre #' . $quiz->id_book }}</p>

                        @php
                            $difficultyClass = match($quiz->difficulty_level) {
                                'beginner' => 'beginner',
                                'intermediate' => 'intermediate',
                                'advanced' => 'advanced',
                                default => 'beginner',
                            };
                            $difficultyText = match($quiz->difficulty_level) {
                                'beginner' => 'Débutant',
                                'intermediate' => 'Intermédiaire',
                                'advanced' => 'Avancé',
                                default => ucfirst($quiz->difficulty_level),
                            };
                        @endphp

                        <span class="difficulty-badge difficulty-{{ $difficultyClass }}">
                            {{ $difficultyText }}
                        </span>
                    </div>

                    <div class="quiz-body">
                        <p class="quiz-description">{{ $quiz->description }}</p>

                        <div class="quiz-stats">
                            <div class="stat-item">
                                <div class="stat-value">{{ $quiz->questions_count ?? $quiz->questions->count() }}</div>
                                <div class="stat-label">Questions</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $quiz->formatted_time_limit ?? $quiz->time_limit }}</div>
                                <div class="stat-label">Durée</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $quiz->max_attempts }}</div>
                                <div class="stat-label">Tentatives</div>
                            </div>
                        </div>

                        @auth
                            @php
                                $userResult = $userResults->get($quiz->id_quiz);
                                $attempts = $userResult ? $userResult->attempt_number : 0;
                                $bestScore = $userResult ? $userResult->percentage : 0;
                                $safeScore = is_numeric($bestScore) ? max(0, min(100, (float)$bestScore)) : 0;
                            @endphp

                            @if($attempts > 0)
                                <div class="user-progress">
                                    <div>Tentatives : {{ $attempts }} / {{ $quiz->max_attempts }}</div>
                                    <div>Meilleur score : {{ number_format($safeScore, 1) }}%</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $safeScore }}%;"></div>
                                    </div>
                                </div>
                            @endif
                        @endauth

                        <div class="quiz-actions">
                            @if(($quiz->questions_count ?? $quiz->questions->count()) > 0)
                                {{-- MODIFICATION ICI: Passer l'ID du book et l'ID du quiz --}}
                                <a href="{{ route('user.quiz.show', [$quiz->id_book, $quiz->id_quiz]) }}" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Commencer
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-clock"></i> Bientôt disponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-quiz">
            <i class="fas fa-book-open"></i>
            <h3>Aucun quiz disponible pour le moment</h3>
            <p>Revenez bientôt pour tester vos connaissances !</p>
        </div>
    @endif
</div>
@endsection
