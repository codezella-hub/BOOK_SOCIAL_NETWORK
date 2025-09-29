@extends('layouts.admin-layout')

@section('title', 'Modifier le Quiz')
@section('page-title', 'Modifier le Quiz')

@section('styles')
<style>
.quiz-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 800px;
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

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #495057;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-actions {
    margin-top: 40px;
    display: flex;
    gap: 15px;
    justify-content: flex-start;
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

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
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
}

.form-hint {
    font-size: 0.85em;
    color: #6c757d;
    margin-top: 4px;
}

.required-field {
    color: #dc3545;
}

.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-success {
    background: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.quiz-info-box {
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 25px;
}

.quiz-info-title {
    font-weight: bold;
    color: #1976d2;
    margin-bottom: 10px;
}

.quiz-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: white;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.stat-number {
    font-size: 1.2em;
    font-weight: bold;
    color: #1976d2;
}

.stat-label {
    font-size: 0.8em;
    color: #666;
}

@media (max-width: 768px) {
    .form-row.two-columns,
    .form-row.three-columns {
        grid-template-columns: 1fr;
    }

    .quiz-form {
        padding: 20px;
        margin: 0 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        justify-content: center;
    }

    .quiz-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <div class="page-header">
        <h2>Modifier le Quiz : {{ $quiz->title }}</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Voir le Quiz
            </a>
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Informations du quiz -->
    <div class="quiz-info-box">
        <div class="quiz-info-title">Informations actuelles du quiz</div>
        <div class="quiz-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $quiz->questions->count() }}</div>
                <div class="stat-label">Questions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $quiz->results->count() }}</div>
                <div class="stat-label">Tentatives</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $quiz->success_rate }}%</div>
                <div class="stat-label">Taux de réussite</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $quiz->created_at->format('d/m/Y') }}</div>
                <div class="stat-label">Date de création</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.quiz.update', $quiz) }}" class="quiz-form">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du Quiz <span class="required-field">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $quiz->title) }}" required
                       placeholder="Entrez le titre du quiz">
                <div class="form-hint">Le titre sera affiché aux utilisateurs</div>
                @error('title')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Description -->
        <div class="form-row">
            <div class="form-group">
                <label for="description">Description <span class="required-field">*</span></label>
                <textarea name="description" id="description" class="form-control"
                          rows="4" required
                          placeholder="Décrivez le contenu et les objectifs du quiz">{{ old('description', $quiz->description) }}</textarea>
                <div class="form-hint">Expliquez ce que couvre ce quiz et ce que les utilisateurs vont apprendre</div>
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Livre et Difficulté -->
        <div class="form-row two-columns">
            <div class="form-group">
                <label for="id_book">Livre <span class="required-field">*</span></label>
                <select name="id_book" id="id_book" class="form-control" required>
                    <option value="">Sélectionner un livre</option>
                    @foreach($bookOptions as $id => $name)
                        <option value="{{ $id }}" {{ old('id_book', $quiz->id_book) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('id_book')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="difficulty_level">Niveau de difficulté <span class="required-field">*</span></label>
                <select name="difficulty_level" id="difficulty_level" class="form-control" required>
                    <option value="">Sélectionner la difficulté</option>
                    @foreach($difficultyLevels as $value => $label)
                        <option value="{{ $value }}" {{ old('difficulty_level', $quiz->difficulty_level) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('difficulty_level')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Paramètres du Quiz -->
        <div class="form-row three-columns">
            <div class="form-group">
                <label for="nb_questions">Nombre de questions <span class="required-field">*</span></label>
                <input type="number" name="nb_questions" id="nb_questions" class="form-control"
                       min="1" max="50" value="{{ old('nb_questions', $quiz->nb_questions) }}" required>
                <div class="form-hint">Entre 1 et 50 questions</div>
                @error('nb_questions')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="max_attempts">Tentatives autorisées <span class="required-field">*</span></label>
                <input type="number" name="max_attempts" id="max_attempts" class="form-control"
                       min="1" max="10" value="{{ old('max_attempts', $quiz->max_attempts) }}" required>
                <div class="form-hint">Maximum 10 tentatives</div>
                @error('max_attempts')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="time_limit">Limite de temps (minutes) <span class="required-field">*</span></label>
                <input type="number" name="time_limit" id="time_limit" class="form-control"
                       min="1" max="180" value="{{ old('time_limit', $quiz->time_limit) }}" required>
                <div class="form-hint">Entre 1 et 180 minutes</div>
                @error('time_limit')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Statut -->
        <div class="form-row">
            <div class="form-group">
                <div class="checkbox-container">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}>
                        <span>Quiz actif</span>
                    </label>
                    <div class="form-hint">Les utilisateurs pourront accéder à ce quiz s'il est actif</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
            <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Voir le Quiz
            </a>
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client
    const form = document.querySelector('.quiz-form');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Validation en temps réel
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });

    // Validation lors de la soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire.');
        } else {
            // Confirmation avant modification
            if (!confirm('Êtes-vous sûr de vouloir modifier ce quiz ?')) {
                e.preventDefault();
            }
        }
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;

        // Supprime les classes d'erreur précédentes
        field.classList.remove('is-invalid');

        // Validation selon le type de champ
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }

        if (field.type === 'number') {
            const min = parseInt(field.getAttribute('min'));
            const max = parseInt(field.getAttribute('max'));
            const numValue = parseInt(value);

            if (value && (numValue < min || numValue > max)) {
                isValid = false;
            }
        }

        // Applique le style d'erreur si nécessaire
        if (!isValid) {
            field.classList.add('is-invalid');
        }

        return isValid;
    }

    // Animation pour les champs
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Alertes pour les modifications importantes
    const nbQuestionsInput = document.getElementById('nb_questions');
    const originalNbQuestions = nbQuestionsInput.value;

    nbQuestionsInput.addEventListener('change', function() {
        if (parseInt(this.value) < parseInt(originalNbQuestions)) {
            alert('Attention : Réduire le nombre de questions peut affecter les questions existantes.');
        }
    });
});
</script>
@endsection
