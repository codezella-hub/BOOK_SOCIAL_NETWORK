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

.btn-primary { background: #007bff; color: white; }
.btn-primary:hover { background: #0056b3; }
.btn-secondary { background: #6c757d; color: white; }
.btn-secondary:hover { background: #545b62; }
.btn-info { background: #17a2b8; color: white; }
.btn-info:hover { background: #138496; }

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

.alert-success {
    background: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <div class="page-header">
        <h2>Modifier le Quiz : {{ $quiz->title }}</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.quiz.show', ['book' => $quiz->id_book, 'quiz' => $quiz->id_quiz]) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Voir le Quiz
            </a>
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.quiz.update', $quiz->id_quiz) }}" class="quiz-form">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du Quiz <span style="color:red">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $quiz->title) }}" required>
                @error('title')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Description -->
        <div class="form-row">
            <div class="form-group">
                <label for="description">Description <span style="color:red">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $quiz->description) }}</textarea>
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Livre et Difficulté -->
        <div class="form-row two-columns">
            <div class="form-group">
                <label for="id_book">Livre <span style="color:red">*</span></label>
                <select name="id_book" id="id_book" class="form-control" required>
                    <option value="">-- Sélectionner un livre --</option>
                    @foreach($bookOptions as $id => $title)
                        <option value="{{ $id }}" {{ old('id_book', $quiz->id_book) == $id ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
                @error('id_book')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="difficulty_level">Difficulté <span style="color:red">*</span></label>
                <select name="difficulty_level" id="difficulty_level" class="form-control" required>
                    <option value="beginner" {{ $quiz->difficulty_level == 'beginner' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediate" {{ $quiz->difficulty_level == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="advanced" {{ $quiz->difficulty_level == 'advanced' ? 'selected' : '' }}>Avancé</option>
                </select>
                @error('difficulty_level')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Paramètres -->
        <div class="form-row three-columns">
            <div class="form-group">
                <label for="nb_questions">Nombre de questions</label>
                <input type="number" name="nb_questions" id="nb_questions" class="form-control"
                       value="{{ old('nb_questions', $quiz->nb_questions) }}" min="1" max="50" required>
            </div>
            <div class="form-group">
                <label for="max_attempts">Tentatives autorisées</label>
                <input type="number" name="max_attempts" id="max_attempts" class="form-control"
                       value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label for="time_limit">Limite de temps (minutes)</label>
                <input type="number" name="time_limit" id="time_limit" class="form-control"
                       value="{{ old('time_limit', $quiz->time_limit) }}" min="1" max="180" required>
            </div>
        </div>

        <!-- Statut -->
        <div class="form-row">
            <div class="form-group">
                <div class="checkbox-container">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ $quiz->is_active ? 'checked' : '' }}>
                        Quiz actif
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder
            </button>

            <a href="{{ route('admin.quiz.show', ['book' => $quiz->id_book, 'quiz' => $quiz->id_quiz]) }}" class="btn btn-info">
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.quiz-form');
    form.addEventListener('submit', function (e) {
        if (!confirm('Confirmer la modification du quiz ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
