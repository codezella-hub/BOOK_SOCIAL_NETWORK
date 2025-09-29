@extends('layouts.user-layout')

@section('title', 'Quiz: ' . $quiz->title)

@section('styles')
<style>
.quiz-detail-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.quiz-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    border-radius: 15px;
    color: white;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.quiz-title {
    font-size: 2.5em;
    margin-bottom: 15px;
    font-weight: bold;
}

.quiz-subtitle {
    font-size: 1.1em;
    opacity: 0.95;
}

.quiz-info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.info-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    text-align: center;
}

.info-icon {
    font-size: 2.5em;
    color: #667eea;
    margin-bottom: 10px;
}

.info-value {
    font-size: 1.8em;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.info-label {
    color: #7f8c8d;
    font-size: 0.9em;
    text-transform: uppercase;
}

.quiz-description {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.description-title {
    font-size: 1.3em;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.description-text {
    color: #555;
    line-height: 1.8;
    font-size: 1.05em;
}

.previous-attempts {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.attempts-title {
    font-size: 1.2em;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
}

.attempts-table {
    width: 100%;
    border-collapse: collapse;
}

.attempts-table th {
    background: #e9ecef;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #495057;
}

.attempts-table td {
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
}

.attempts-table tr:hover {
    background: #f1f3f5;
}

.score-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9em;
}

.score-high {
    background: #c6f6d5;
    color: #22543d;
}

.score-medium {
    background: #fef5e7;
    color: #7d6608;
}

.score-low {
    background: #fed7d7;
    color: #9b2c2c;
}

.quiz-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    text-decoration: none;
    color: white;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
    text-decoration: none;
    color: #4a5568;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.alert {
    padding: 15px 20px;
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

.alert-warning {
    background: #fef5e7;
    color: #7d6608;
    border: 1px solid #f6e05e;
}

.rules-section {
    background: #fff5f5;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    border-left: 4px solid #e53e3e;
}

.rules-title {
    font-size: 1.2em;
    font-weight: bold;
    color: #e53e3e;
    margin-bottom: 15px;
}

.rules-list {
    list-style: none;
    padding: 0;
}

.rules-list li {
    padding: 8px 0;
    color: #555;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.rules-list i {
    color: #e53e3e;
    margin-top: 3px;
}

.no-attempts {
    text-align: center;
    padding: 20px;
    color: #7f8c8d;
    font-style: italic;
}

/* Styles pour les écrans mobiles */
@media (max-width: 768px) {
    .quiz-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .quiz-info-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection

@section('content')
<div class="quiz-detail-container">
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('warning') }}
        </div>
    @endif

    <div class="quiz-header">
        <h1 class="quiz-title">{{ $quiz->title }}</h1>
        <p class="quiz-subtitle">Testez vos connaissances maintenant !</p>
    </div>

    <div class="quiz-info-cards">
        <div class="info-card">
            <div class="info-icon"><i class="fas fa-question-circle"></i></div>
            <div class="info-value">{{ $quiz->nb_questions }}</div>
            <div class="info-label">Questions</div>
        </div>

        <div class="info-card">
            <div class="info-icon"><i class="fas fa-clock"></i></div>
            <div class="info-value">{{ $quiz->time_limit }}</div>
            <div class="info-label">Minutes</div>
        </div>

        <div class="info-card">
            <div class="info-icon"><i class="fas fa-redo"></i></div>
            <div class="info-value">{{ $quiz->max_attempts }}</div>
            <div class="info-label">Tentatives max</div>
        </div>

        <div class="info-card">
            <div class="info-icon"><i class="fas fa-trophy"></i></div>
            <div class="info-value">70%</div>
            <div class="info-label">Pour réussir</div>
        </div>
    </div>

    <div class="quiz-description">
        <h3 class="description-title">
            <i class="fas fa-info-circle"></i> Description du quiz
        </h3>
        <p class="description-text">{{ $quiz->description }}</p>
    </div>

    <div class="rules-section">
        <h3 class="rules-title">
            <i class="fas fa-exclamation-triangle"></i> Règles importantes
        </h3>
        <ul class="rules-list">
            <li><i class="fas fa-check"></i> Vous avez {{ $quiz->time_limit }} minutes pour compléter le quiz</li>
            <li><i class="fas fa-check"></i> Le quiz contient {{ $quiz->nb_questions }} questions</li>
            <li><i class="fas fa-check"></i> Vous devez obtenir au moins 70% pour réussir</li>
            <li><i class="fas fa-check"></i> Maximum {{ $quiz->max_attempts }} tentatives autorisées</li>
            @auth
                <li><i class="fas fa-check"></i> Tentatives utilisées : {{ $attemptsCount }} / {{ $quiz->max_attempts }}</li>
            @else
                <li><i class="fas fa-info"></i> Connectez-vous pour sauvegarder votre progression</li>
            @endauth
        </ul>
    </div>

    @auth
        @if($previousResults->count() > 0)
            <div class="previous-attempts">
                <h3 class="attempts-title">
                    <i class="fas fa-history"></i> Vos tentatives précédentes
                </h3>
                <table class="attempts-table">
                    <thead>
                        <tr>
                            <th>Tentative</th>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Résultat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previousResults as $result)
                            <tr>
                                <td>#{{ $result->attempt_number }}</td>
                                <td>{{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'Non terminé' }}</td>
                                <td>
                                    @php
                                        $scoreClass = $result->percentage >= 70 ? 'score-high' :
                                                     ($result->percentage >= 50 ? 'score-medium' : 'score-low');
                                    @endphp
                                    <span class="score-badge {{ $scoreClass }}">
                                        {{ number_format($result->percentage, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    @if($result->passed)
                                        <span style="color: #22543d;"><i class="fas fa-check-circle"></i> Réussi</span>
                                    @else
                                        <span style="color: #9b2c2c;"><i class="fas fa-times-circle"></i> Échoué</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endauth

    <!-- SECTION DES ACTIONS DU QUIZ - CORRIGÉE -->
    <div class="quiz-actions">
        <a href="{{ route('user.quiz.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux quiz
        </a>

        @if($quiz->questions->count() > 0)
            @auth
                @if($attemptsCount < $quiz->max_attempts)
                    <a href="{{ route('user.quiz.start', $quiz->id_quiz) }}" class="btn btn-primary">
                        <i class="fas fa-play"></i> Commencer le quiz
                    </a>
                @else
                    <button class="btn btn-primary" disabled>
                        <i class="fas fa-ban"></i> Tentatives épuisées
                    </button>
                @endif
            @else
                <a href="{{ route('user.quiz.start', $quiz->id_quiz) }}" class="btn btn-primary">
                    <i class="fas fa-play"></i> Commencer le quiz (non sauvegardé)
                </a>
            @endauth
        @else
            <button class="btn btn-primary" disabled>
                <i class="fas fa-clock"></i> Quiz en préparation
            </button>
        @endif
    </div>
</div>
@endsection
