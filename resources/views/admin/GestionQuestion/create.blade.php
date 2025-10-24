@extends('layouts.admin-layout')

@section('title', 'Ajouter une Question')
@section('page-title', 'Nouvelle Question')

@section('styles')
<style>
.quiz-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.form-row.two-columns {
    grid-template-columns: 1fr 1fr;
}

.form-row.three-columns {
    grid-template-columns: 1fr 1fr 1fr;
}

.form-row.four-columns {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

.form-control {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
}

.form-control:invalid {
    border-color: #dc3545;
}

.error {
    color: #dc3545;
    font-size: 0.85em;
    margin-top: 5px;
    font-weight: 500;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.85em;
    margin-top: 5px;
    font-weight: 500;
}

.info-box {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 25px;
}

.info-box-header {
    font-weight: bold;
    color: #0056b3;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-box-content {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 14px;
    color: #495057;
}

.info-box-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.info-box-item strong {
    color: #212529;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.section-header i {
    color: #007bff;
}

.section-header h5 {
    margin: 0;
    color: #212529;
    font-size: 1.1em;
}

.option-input-group {
    display: flex;
    align-items: stretch;
}

.option-label {
    background: #007bff;
    color: white;
    padding: 12px 20px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px 0 0 6px;
    min-width: 50px;
}

.option-input-group .form-control {
    border-radius: 0 6px 6px 0;
    border-left: none;
}

.correct-answer-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.answer-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.answer-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.answer-option:hover {
    border-color: #007bff;
    background: #f0f7ff;
}

.answer-option input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.answer-option label {
    cursor: pointer;
    margin: 0;
    font-weight: 500;
}

.form-actions {
    margin-top: 40px;
    display: flex;
    gap: 15px;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-outline-secondary {
    background: white;
    color: #6c757d;
    border: 1px solid #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.btn-group {
    display: flex;
    gap: 10px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.page-header h2 {
    margin: 0;
    color: #212529;
    font-size: 1.8em;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-hint {
    font-size: 0.85em;
    color: #6c757d;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.required-field {
    color: #dc3545;
}

.help-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    margin-top: 30px;
    border: 1px solid #e9ecef;
}

.help-box h6 {
    font-weight: bold;
    margin-bottom: 15px;
    color: #212529;
    display: flex;
    align-items: center;
    gap: 10px;
}

.help-box ul {
    margin: 0;
    padding-left: 20px;
}

.help-box li {
    margin-bottom: 8px;
    color: #495057;
    font-size: 0.9em;
}

.border-warning {
    border-color: #ffc107 !important;
    border-width: 2px !important;
}

@media (max-width: 768px) {
    .form-row.two-columns,
    .form-row.three-columns,
    .form-row.four-columns {
        grid-template-columns: 1fr;
    }

    .quiz-form {
        padding: 20px;
        margin: 0 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn {
        justify-content: center;
        width: 100%;
    }

    .answer-options {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <div class="page-header">
        <h2>
            <i class="fas fa-plus-circle"></i>
            Ajouter une nouvelle question
        </h2>
        <a href="{{ route('admin.quiz.question.index', $quiz->id_quiz) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-box-header">
            <i class="fas fa-info-circle"></i>
            <span>Quiz: {{ $quiz->title }}</span>
        </div>
        <div class="info-box-content">
            <div class="info-box-item">
                <strong>Questions actuelles:</strong>
                <span>{{ $quiz->questions->count() }} / {{ $quiz->nb_questions }}</span>
            </div>
            <div class="info-box-item">
                <strong>Prochaine position:</strong>
                <span>#{{ $nextPosition }}</span>
            </div>
            <div class="info-box-item">
                <strong>Difficulté:</strong>
                <span>{{ ucfirst($quiz->difficulty_level) }}</span>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.quiz.question.store', $quiz->id_quiz) }}" id="questionForm" class="quiz-form">
        @csrf

        <!-- Question Text -->
        <div class="form-row">
            <div class="form-group">
                <label for="question_text">Question <span class="required-field">*</span></label>
                <textarea name="question_text"
                          id="question_text"
                          class="form-control @error('question_text') is-invalid @enderror"
                          rows="4"
                          required
                          placeholder="Écrivez votre question ici...">{{ old('question_text') }}</textarea>
                <div class="form-hint">
                    <i class="fas fa-lightbulb" style="color: #ffc107;"></i>
                    Soyez clair et précis dans la formulation de votre question
                </div>
                @error('question_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Options de réponse -->
        <div class="section-header">
            <i class="fas fa-list-ul"></i>
            <h5>Options de réponse</h5>
        </div>

        <div class="form-row four-columns">
            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
            <div class="form-group">
                <label for="option_{{ $key }}">Option {{ $label }} <span class="required-field">*</span></label>
                <div class="option-input-group">
                    <span class="option-label">{{ $label }}</span>
                    <input type="text"
                           name="option_{{ $key }}"
                           id="option_{{ $key }}"
                           class="form-control @error('option_'.$key) is-invalid @enderror"
                           value="{{ old('option_'.$key) }}"
                           required
                           placeholder="Entrez l'option {{ $label }}">
                </div>
                @error('option_'.$key)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endforeach
        </div>

        <!-- Réponse correcte et points -->
        <div class="section-header" style="margin-top: 30px;">
            <i class="fas fa-check-circle"></i>
            <h5>Configuration de la question</h5>
        </div>

        <div class="form-row three-columns">
            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label">Réponse correcte <span class="required-field">*</span></label>
                <div class="correct-answer-section">
                    <div class="answer-options">
                        @foreach(['A', 'B', 'C', 'D'] as $option)
                        <div class="answer-option">
                            <input class="form-check-input"
                                   type="radio"
                                   name="correct_answer"
                                   id="answer_{{ $option }}"
                                   value="{{ $option }}"
                                   {{ old('correct_answer') == $option ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="answer_{{ $option }}">
                                Option {{ $option }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @error('correct_answer')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="points">Points <span class="required-field">*</span></label>
                <input type="number"
                       name="points"
                       id="points"
                       class="form-control @error('points') is-invalid @enderror"
                       value="{{ old('points', 1) }}"
                       min="0"
                       max="100"
                       step="0.5"
                       required>
                <div class="form-hint">Entre 0 et 100 points</div>
                @error('points')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="order_position">Position dans le quiz <span class="required-field">*</span></label>
                <input type="number"
                       name="order_position"
                       id="order_position"
                       class="form-control @error('order_position') is-invalid @enderror"
                       value="{{ old('order_position', $nextPosition) }}"
                       min="1"
                       required>
                <div class="form-hint">Position d'affichage de la question dans le quiz</div>
                @error('order_position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.quiz.question.index', $quiz->id_quiz) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <div class="btn-group">
                <button type="submit" name="action" value="save" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="submit" name="action" value="save_and_new" class="btn btn-success">
                    <i class="fas fa-plus"></i> Enregistrer et ajouter une autre
                </button>
            </div>
        </div>
    </form>

    <!-- Aide contextuelle -->
    <div class="help-box">
        <h6>
            <i class="fas fa-question-circle" style="color: #17a2b8;"></i>
            Conseils pour créer une bonne question
        </h6>
        <ul>
            <li>Formulez des questions claires et sans ambiguïté</li>
            <li>Assurez-vous que les options de réponse sont distinctes</li>
            <li>Évitez les doubles négations dans vos questions</li>
            <li>Variez la difficulté pour maintenir l'intérêt des participants</li>
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionForm');
    const optionInputs = document.querySelectorAll('[name^="option_"]');
    const radioButtons = document.querySelectorAll('[name="correct_answer"]');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Auto-sélection de la réponse correcte au focus d'une option
    optionInputs.forEach((input, index) => {
        input.addEventListener('focus', function() {
            const letter = String.fromCharCode(65 + index); // A, B, C, D
            const radio = document.getElementById('answer_' + letter);
            if (radio) {
                radio.checked = true;
            }
        });
    });

    // Animation pour les champs
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        const checkedAnswer = document.querySelector('[name="correct_answer"]:checked');

        if (!checkedAnswer) {
            e.preventDefault();
            alert('Veuillez sélectionner la réponse correcte !');
            return false;
        }

        // Validation des options uniques
        const options = Array.from(optionInputs).map(input => input.value.trim()).filter(v => v);
        const uniqueOptions = [...new Set(options)];

        if (options.length > 0 && uniqueOptions.length !== options.length) {
            if (!confirm('Attention : Certaines options semblent identiques. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Validation temps réel des options
    let validationTimeout;
    optionInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(validateOptions, 500);
        });
    });

    function validateOptions() {
        const options = Array.from(optionInputs)
            .map(input => input.value.trim())
            .filter(v => v);

        const uniqueOptions = [...new Set(options)];

        optionInputs.forEach(input => {
            if (input.value.trim()) {
                const duplicates = options.filter(opt => opt === input.value.trim()).length;
                if (duplicates > 1) {
                    input.classList.add('border-warning');
                } else {
                    input.classList.remove('border-warning');
                }
            }
        });
    }
});
</script>
@endsection
