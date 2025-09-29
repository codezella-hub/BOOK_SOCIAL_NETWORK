@extends('layouts.user-layout')

@section('title', 'Résultats du Quiz')

@section('styles')
<style>
.result-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.result-header {
    text-align: center;
    padding: 40px;
    border-radius: 15px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.result-header.success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

.result-header.fail {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
}

.result-icon {
    font-size: 4em;
    margin-bottom: 20px;
}

.result-title {
    font-size: 2.5em;
    margin-bottom: 10px;
    font-weight: bold;
}

.result-message {
    font-size: 1.2em;
    opacity: 0.95;
}

.score-display {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    text-align: center;
}

.score-circle {
    width: 200px;
    height: 200px;
    margin: 0 auto 30px;
    position: relative;
}

.circle-background {
    fill: none;
    stroke: #e9ecef;
    stroke-width: 10;
}

.circle-progress {
    fill: none;
    stroke-width: 10;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    transition: stroke-dashoffset 1s ease;
}

.circle-progress.high { stroke: #48bb78; }
.circle-progress.medium { stroke: #f6e05e; }
.circle-progress.low { stroke: #f56565; }

.score-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3em;
    font-weight: bold;
}

.score-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.detail-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.detail-value {
    font-size: 1.5em;
    font-weight: bold;
    color: #2c3e50;
}

.detail-label {
    font-size: 0.9em;
    color: #7f8c8d;
    margin-top: 5px;
}

.review-section {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.review-title {
    font-size: 1.5em;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.question-review {
    margin-bottom: 25px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #dee2e6;
}

.question-review.correct {
    border-left-color: #48bb78;
}

.question-review.incorrect {
    border-left-color: #f56565;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.review-question {
    font-weight: 600;
    color: #2c3e50;
    flex: 1;
}

.review-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: bold;
}

.review-status.correct {
    background: #c6f6d5;
    color: #22543d;
}

.review-status.incorrect {
    background: #fed7d7;
    color: #9b2c2c;
}

.review-options {
    display: grid;
    gap: 10px;
    margin-top: 15px;
}

.review-option {
    padding: 10px 15px;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.review-option.user-answer {
    border: 2px solid #f56565;
}

.review-option.correct-answer {
    border: 2px solid #48bb78;
    background: #f0fdf4;
}

.review-option.user-correct {
    border: 2px solid #48bb78;
}

.option-marker {
    font-weight: bold;
    color: #495057;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 40px;
}

.btn {
    padding: 15px 30px;
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
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.save-notice {
    background: #e3f2fd;
    color: #1976d2;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

@media (max-width: 768px) {
    .score-circle {
        width: 150px;
        height: 150px;
    }

    .score-text {
        font-size: 2em;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<div class="result-container">
    <div class="result-header {{ $passed ? 'success' : 'fail' }}">
        <div class="result-icon">
            @if($passed)
                <i class="fas fa-trophy"></i>
            @else
                <i class="fas fa-times-circle"></i>
            @endif
        </div>
        <h1 class="result-title">
            @if($passed)
                Félicitations !
            @else
                Dommage !
            @endif
        </h1>
        <p class="result-message">
            @if($passed)
                Vous avez réussi le quiz "{{ $quiz->title }}" !
            @else
                Vous n'avez pas réussi le quiz "{{ $quiz->title }}". Réessayez !
            @endif
        </p>
    </div>

    @if($result && Auth::check())
        <div class="save-notice">
            <i class="fas fa-check-circle"></i>
            Votre résultat a été sauvegardé avec succès
        </div>
    @elseif(!Auth::check())
        <div class="save-notice" style="background: #fef5e7; color: #7d6608;">
            <i class="fas fa-info-circle"></i>
            Connectez-vous pour sauvegarder vos résultats
        </div>
    @endif

    <div class="score-display">
        <div class="score-circle">
            <svg width="200" height="200">
                <circle cx="100" cy="100" r="90" class="circle-background"></circle>
                <circle cx="100" cy="100" r="90"
                    class="circle-progress {{ $percentage >= 70 ? 'high' : ($percentage >= 50 ? 'medium' : 'low') }}"
                    style="stroke-dasharray: {{ 2 * 3.14159 * 90 }};
                           stroke-dashoffset: {{ 2 * 3.14159 * 90 * (1 - $percentage / 100) }}">
                </circle>
            </svg>
            <div class="score-text">{{ number_format($percentage, 0) }}%</div>
        </div>

        <div class="score-details">
            <div class="detail-item">
                <div class="detail-value">{{ $correctAnswers }}</div>
                <div class="detail-label">Réponses correctes</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ count($reviewData) }}</div>
                <div class="detail-label">Total questions</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $earnedPoints }}</div>
                <div class="detail-label">Points obtenus</div>
            </div>
            <div class="detail-item">
                <div class="detail-value">{{ $totalPoints }}</div>
                <div class="detail-label">Points totaux</div>
            </div>
        </div>
    </div>

    <div class="review-section">
        <h2 class="review-title">
            <i class="fas fa-clipboard-check"></i> Révision des réponses
        </h2>

        @foreach($reviewData as $index => $review)
            <div class="question-review {{ $review['is_correct'] ? 'correct' : 'incorrect' }}">
                <div class="review-header">
                    <div class="review-question">
                        <span>Q{{ $index + 1 }}. </span>
                        {{ $review['question']->question_text }}
                    </div>
                    <div class="review-status {{ $review['is_correct'] ? 'correct' : 'incorrect' }}">
                        @if($review['is_correct'])
                            <i class="fas fa-check"></i> Correct
                        @else
                            <i class="fas fa-times"></i> Incorrect
                        @endif
                    </div>
                </div>
                <div class="review-options">
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                        @php
                            $optionKey = 'option_' . strtolower($opt);
                            $isUserAnswer = $review['user_answer'] === $opt;
                            $isCorrectAnswer = $review['question']->correct_answer === $opt;
                        @endphp
                        <div class="review-option
                            {{ $isUserAnswer && !$isCorrectAnswer ? 'user-answer' : '' }}
                            {{ $isCorrectAnswer ? 'correct-answer' : '' }}
                            {{ $isUserAnswer && $isCorrectAnswer ? 'user-correct' : '' }}">
                            <span class="option-marker">{{ $opt }}.</span>
                            <span>{{ $review['question']->$optionKey }}</span>
                            @if($isUserAnswer && $isCorrectAnswer)
                                <span style="color:#48bb78;"><i class="fas fa-check-circle"></i> Votre réponse</span>
                            @elseif($isUserAnswer)
                                <span style="color:#f56565;"><i class="fas fa-times-circle"></i> Votre réponse</span>
                            @elseif($isCorrectAnswer)
                                <span style="color:#38a169;"><i class="fas fa-star"></i> Bonne réponse</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="action-buttons">
        <a href="{{ route('user.quiz.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux quiz
        </a>
        <a href="{{ route('user.quiz.show', $quiz) }}" class="btn btn-primary">
            <i class="fas fa-redo"></i> Recommencer ce quiz
        </a>
        @if(Auth::check())
            <a href="{{ route('user.quiz.history') }}" class="btn btn-primary">
                <i class="fas fa-history"></i> Voir mon historique
            </a>
        @endif
    </div>
</div>
@endsection
