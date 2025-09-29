@extends('layouts.user-layout')

@section('title', 'Quiz en cours: ' . $quiz->title)

@section('styles')
<style>
.quiz-play-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.quiz-progress {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    position: sticky;
    top: 20px;
    z-index: 100;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.quiz-title-small {
    font-size: 1.2em;
    font-weight: bold;
    color: #2c3e50;
}

.timer {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1em;
    font-weight: bold;
    padding: 8px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s;
}

.timer.warning {
    background: #fef5e7;
    color: #f39c12;
}

.timer.danger {
    background: #fed7d7;
    color: #e74c3c;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.progress-bar-container {
    background: #e9ecef;
    height: 10px;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
}

.question-counter {
    text-align: center;
    color: #7f8c8d;
    margin-top: 10px;
    font-size: 0.9em;
}

.questions-form {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.question-block {
    margin-bottom: 40px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.question-header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
}

.question-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.question-text {
    font-size: 1.1em;
    color: #2c3e50;
    line-height: 1.6;
    flex: 1;
}

.question-points {
    background: #e3f2fd;
    color: #1976d2;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: bold;
}

.options-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.option-item {
    margin-bottom: 12px;
}

.option-label {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.option-label:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.option-label.selected {
    border-color: #667eea;
    background: #e3f2fd;
}

.option-radio {
    margin-right: 15px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.option-letter {
    background: #e9ecef;
    color: #495057;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    transition: all 0.3s;
}

.option-label.selected .option-letter {
    background: #667eea;
    color: white;
}

.option-text {
    flex: 1;
    color: #2c3e50;
}

.quiz-actions {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
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
    text-align: center;
    justify-content: center;
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

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
}

.warning-message {
    background: #fef5e7;
    color: #7d6608;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #f6e05e;
}

@media (max-width: 768px) {
    .question-block {
        padding: 15px;
    }

    .option-label {
        padding: 12px;
    }

    .quiz-actions {
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
<div class="quiz-play-container">
    <div class="quiz-progress">
        <div class="progress-header">
            <h2 class="quiz-title-small">{{ $quiz->title }}</h2>
            <div class="timer" id="timer">
                <i class="fas fa-clock"></i>
                <span id="time-remaining">{{ $quiz->time_limit }}:00</span>
            </div>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progress-bar" style="width: 0%"></div>
        </div>
        <div class="question-counter">
            Question <span id="current-question">0</span> sur {{ $questions->count() }}
        </div>
    </div>

    @if(!Auth::check())
        <div class="warning-message">
            <i class="fas fa-info-circle"></i>
            Vous n'êtes pas connecté. Votre résultat ne sera pas sauvegardé.
        </div>
    @endif

    <form method="POST" action="{{ route('user.quiz.submit', $quiz->id_quiz) }}" id="quiz-form" class="questions-form">
        @csrf

        @foreach($questions as $index => $question)
            <div class="question-block" data-question="{{ $index + 1 }}">
                <div class="question-header">
                    <div class="question-number">{{ $index + 1 }}</div>
                    <div class="question-text">{{ $question->question_text }}</div>
                    <div class="question-points">{{ $question->points }} pts</div>
                </div>

                <ul class="options-list">
                    @foreach(['A', 'B', 'C', 'D'] as $option)
                        @php
                            $optionField = 'option_' . strtolower($option);
                        @endphp
                        @if(!empty($question->$optionField))
                            <li class="option-item">
                                <label class="option-label">
                                    <input type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $option }}"
                                           class="option-radio"
                                           data-question-index="{{ $index + 1 }}"
                                           required>
                                    <span class="option-letter">{{ $option }}</span>
                                    <span class="option-text">{{ $question->$optionField }}</span>
                                </label>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="quiz-actions">
            <a href="{{ route('user.quiz.show', $quiz->id_quiz) }}" class="btn btn-secondary"
               onclick="return confirm('Êtes-vous sûr de vouloir abandonner le quiz ? Votre progression sera perdue.')">
                <i class="fas fa-times"></i> Abandonner
            </a>
            <button type="submit" class="btn btn-success" id="submit-btn">
                <i class="fas fa-check"></i> Soumettre les réponses
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer
    const timeLimit = {{ $quiz->time_limit }} * 60; // en secondes
    let timeRemaining = timeLimit;
    const timerElement = document.getElementById('timer');
    const timeDisplay = document.getElementById('time-remaining');
    const submitBtn = document.getElementById('submit-btn');

    const timerInterval = setInterval(function() {
        timeRemaining--;

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        // Changer la couleur du timer
        timerElement.classList.remove('warning', 'danger');
        if (timeRemaining <= 60) {
            timerElement.classList.add('danger');
        } else if (timeRemaining <= 300) {
            timerElement.classList.add('warning');
        }

        // Auto-submit quand le temps est écoulé
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            alert('Le temps est écoulé ! Le quiz va être soumis automatiquement.');
            document.getElementById('quiz-form').submit();
        }
    }, 1000);

    // Mise à jour de la barre de progression
    const updateProgress = function() {
        const totalQuestions = {{ $questions->count() }};
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        const progressPercent = (answeredQuestions / totalQuestions) * 100;

        document.getElementById('progress-bar').style.width = progressPercent + '%';
        document.getElementById('current-question').textContent = answeredQuestions;
    };

    // Style pour les options sélectionnées
    document.querySelectorAll('.option-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Enlever la classe selected des autres options de la même question
            const questionBlock = this.closest('.question-block');
            questionBlock.querySelectorAll('.option-label').forEach(label => {
                label.classList.remove('selected');
            });

            // Ajouter la classe selected à l'option choisie
            this.closest('.option-label').classList.add('selected');

            // Mettre à jour la progression
            updateProgress();
        });
    });

    // Validation avant soumission
    document.getElementById('quiz-form').addEventListener('submit', function(e) {
        const totalQuestions = {{ $questions->count() }};
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;

        if (answeredQuestions < totalQuestions) {
            e.preventDefault();
            if (confirm(`Vous n'avez répondu qu'à ${answeredQuestions} questions sur ${totalQuestions}. Voulez-vous vraiment soumettre ?`)) {
                clearInterval(timerInterval);
                this.submit();
            }
        } else {
            clearInterval(timerInterval);
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Soumission en cours...';
        }
    });

    // Prévenir la navigation accidentelle
    let preventNavigation = true;
    window.addEventListener('beforeunload', function(e) {
        if (preventNavigation) {
            e.preventDefault();
            e.returnValue = '';
            return 'Voulez-vous vraiment quitter ? Votre progression sera perdue.';
        }
    });

    // Permettre la navigation quand le formulaire est soumis
    document.getElementById('quiz-form').addEventListener('submit', function() {
        preventNavigation = false;
    });

    // Initialiser la progression
    updateProgress();
});
</script>
@endsection
