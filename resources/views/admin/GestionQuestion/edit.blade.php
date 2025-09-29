@extends('layouts.admin-layout')

@section('title', 'Modifier la Question')
@section('page-title', 'Modifier la Question')

@section('styles')
<style>
.edit-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.form-card {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    margin: -30px -30px 30px -30px;
}

.form-header h2 {
    margin: 0;
    font-size: 1.5em;
}

.form-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #f5576c;
    box-shadow: 0 0 0 3px rgba(245,87,108,0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.options-section {
    background: #fff5f5;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.option-group {
    position: relative;
}

.option-label {
    display: inline-block;
    padding: 3px 8px;
    background: #f5576c;
    color: white;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 5px;
}

.correct-answer-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.radio-group {
    display: flex;
    gap: 20px;
    padding: 15px;
    background: #fce4ec;
    border-radius: 5px;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    cursor: pointer;
}

.radio-option label {
    cursor: pointer;
    margin: 0;
    font-weight: 500;
}

.points-input {
    width: 150px;
}

.form-hint {
    display: block;
    font-size: 0.85em;
    color: #6c757d;
    margin-top: 5px;
}

.error {
    color: #dc3545;
    font-size: 0.85em;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #dee2e6;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.info-box {
    background: #e8f5e9;
    border-left: 4px solid #4caf50;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 0 5px 5px 0;
}

.info-box-title {
    font-weight: bold;
    color: #2e7d32;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .options-grid,
    .correct-answer-section {
        grid-template-columns: 1fr;
    }

    .radio-group {
        flex-direction: column;
        gap: 10px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<div class="edit-container">
    <div class="form-card">
        <div class="form-header">
            <h2>Modifier la question #{{ $question->order_position }}</h2>
            <p>Quiz: {{ $quiz->title }}</p>
        </div>

        <!-- Information sur la question -->
        <div class="info-box">
            <div class="info-box-title">Informations actuelles</div>
            <div>Position: {{ $question->order_position }} / {{ $quiz->questions->count() }}</div>
            <div>Points actuels: {{ $question->points }}</div>
            <div>Créée le: {{ $question->created_at->format('d/m/Y H:i') }}</div>
        </div>

        @if(session('success'))
            <div class="alert alert-info">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.question.update', [$quiz, $question]) }}" id="questionEditForm">
            @csrf
            @method('PUT')

            <!-- Question -->
            <div class="form-group">
                <label for="question_text">
                    Question <span class="required">*</span>
                </label>
                <textarea name="question_text" id="question_text" class="form-control"
                          required placeholder="Écrivez votre question ici...">{{ old('question_text', $question->question_text) }}</textarea>
                <span class="form-hint">Soyez clair et précis dans la formulation de votre question</span>
                @error('question_text')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Options de réponse -->
            <div class="options-section">
                <h3>Options de réponse</h3>
                <div class="options-grid">
                    <div class="option-group">
                        <span class="option-label">A</span>
                        <input type="text" name="option_a" id="option_a" class="form-control"
                               value="{{ old('option_a', $question->option_a) }}" required
                               placeholder="Option A">
                        @error('option_a')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="option-group">
                        <span class="option-label">B</span>
                        <input type="text" name="option_b" id="option_b" class="form-control"
                               value="{{ old('option_b', $question->option_b) }}" required
                               placeholder="Option B">
                        @error('option_b')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="option-group">
                        <span class="option-label">C</span>
                        <input type="text" name="option_c" id="option_c" class="form-control"
                               value="{{ old('option_c', $question->option_c) }}" required
                               placeholder="Option C">
                        @error('option_c')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="option-group">
                        <span class="option-label">D</span>
                        <input type="text" name="option_d" id="option_d" class="form-control"
                               value="{{ old('option_d', $question->option_d) }}" required
                               placeholder="Option D">
                        @error('option_d')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Réponse correcte et points -->
            <div class="correct-answer-section">
                <div class="form-group">
                    <label>Réponse correcte <span class="required">*</span></label>
                    <div class="radio-group">
                        @foreach(['A', 'B', 'C', 'D'] as $option)
                        <div class="radio-option">
                            <input type="radio" name="correct_answer" id="answer_{{ $option }}"
                                   value="{{ $option }}"
                                   {{ old('correct_answer', $question->correct_answer) == $option ? 'checked' : '' }} required>
                            <label for="answer_{{ $option }}">Option {{ $option }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('correct_answer')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="points">Points <span class="required">*</span></label>
                    <input type="number" name="points" id="points" class="form-control points-input"
                           value="{{ old('points', $question->points) }}" min="0" max="100" step="0.5" required>
                    <span class="form-hint">Nombre de points pour cette question (0-100)</span>
                    @error('points')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Position -->
            <div class="form-group">
                <label for="order_position">Position dans le quiz <span class="required">*</span></label>
                <input type="number" name="order_position" id="order_position" class="form-control points-input"
                       value="{{ old('order_position', $question->order_position) }}" min="1" max="{{ $quiz->questions->count() }}" required>
                <span class="form-hint">Position de la question dans l'ordre du quiz (1-{{ $quiz->questions->count() }})</span>
                @error('order_position')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('admin.question.show', [$quiz, $question]) }}" class="btn btn-warning">
                    <i class="fas fa-eye"></i> Voir la question
                </a>
                <a href="{{ route('admin.question.index', $quiz) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionEditForm');
    const optionInputs = document.querySelectorAll('[name^="option_"]');

    // Validation des options uniques
    function validateOptions() {
        const options = Array.from(optionInputs).map(input => input.value.trim().toLowerCase());
        const uniqueOptions = [...new Set(options)];

        if (uniqueOptions.length !== options.length && options.every(opt => opt !== '')) {
            alert('Attention: Les options doivent être uniques!');
            return false;
        }
        return true;
    }

    // Validation en temps réel
    optionInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateOptions();
        });
    });

    // Confirmation avant modification importante
    const originalPosition = {{ $question->order_position }};
    const positionInput = document.getElementById('order_position');

    positionInput.addEventListener('change', function() {
        if (parseInt(this.value) !== originalPosition) {
            if (!confirm('Changer la position réorganisera les autres questions. Continuer?')) {
                this.value = originalPosition;
            }
        }
    });

    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        if (!validateOptions()) {
            e.preventDefault();
            return;
        }

        const checkedAnswer = document.querySelector('[name="correct_answer"]:checked');
        if (!checkedAnswer) {
            e.preventDefault();
            alert('Veuillez sélectionner la réponse correcte!');
            return;
        }

        if (!confirm('Êtes-vous sûr de vouloir modifier cette question?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
