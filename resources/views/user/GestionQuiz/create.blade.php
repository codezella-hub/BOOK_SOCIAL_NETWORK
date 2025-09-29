@extends('admin.layout')

@section('title', 'Créer un Quiz')
@section('page-title', 'Créer un Quiz')

@section('content')
<div class="admin-content">
    <div class="page-header">
        <h2>Créer un nouveau Quiz</h2>
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <form method="POST" action="{{ route('admin.quiz.store') }}" class="quiz-form">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du Quiz *</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title') }}" required>
                @error('title')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea name="description" id="description" class="form-control"
                          rows="4" required>{{ old('description') }}</textarea>
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_book">Livre *</label>
                <select name="id_book" id="id_book" class="form-control" required>
                    <option value="">Sélectionner un livre</option>
                    @foreach($bookOptions as $id => $name)
                        <option value="{{ $id }}" {{ old('id_book') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('id_book')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="difficulty_level">Niveau de difficulté *</label>
                <select name="difficulty_level" id="difficulty_level" class="form-control" required>
                    <option value="">Sélectionner la difficulté</option>
                    @foreach($difficultyLevels as $value => $label)
                        <option value="{{ $value }}" {{ old('difficulty_level') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('difficulty_level')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nb_questions">Nombre de questions *</label>
                <input type="number" name="nb_questions" id="nb_questions" class="form-control"
                       min="1" max="50" value="{{ old('nb_questions', 5) }}" required>
                @error('nb_questions')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="max_attempts">Tentatives autorisées *</label>
                <input type="number" name="max_attempts" id="max_attempts" class="form-control"
                       min="1" max="10" value="{{ old('max_attempts', 3) }}" required>
                @error('max_attempts')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="time_limit">Limite de temps (minutes) *</label>
                <input type="number" name="time_limit" id="time_limit" class="form-control"
                       min="1" max="180" value="{{ old('time_limit', 30) }}" required>
                @error('time_limit')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    Quiz actif
                </label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Créer le Quiz
            </button>
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
