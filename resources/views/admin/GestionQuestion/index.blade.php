@extends('layouts.admin-layout')

@section('title', 'Gestion des Questions')
@section('page-title', 'Questions du Quiz: ' . $quiz->title)

@section('styles')
<style>
.questions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.quiz-info-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.quiz-info-bar h2 {
    margin: 0 0 10px 0;
    font-size: 1.5em;
}

.quiz-meta {
    display: flex;
    gap: 30px;
    margin-top: 15px;
}

.quiz-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.questions-table {
    width: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.questions-table thead {
    background: #f8f9fa;
}

.questions-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.questions-table td {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
}

.questions-table tr:hover {
    background: #f8f9fa;
}

.question-text {
    max-width: 400px;
    line-height: 1.4;
}

.options-list {
    list-style: none;
    margin: 10px 0 0 0;
    padding: 0;
    font-size: 0.9em;
}

.options-list li {
    padding: 3px 0;
}

.correct-option {
    color: #28a745;
    font-weight: bold;
}

.badge {
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 0.85em;
    font-weight: 500;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-info {
    background: #d1ecf1;
    color: #0c5460;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.85em;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.empty-state i {
    font-size: 4em;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 30px;
}

.position-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 3px 8px;
    border-radius: 3px;
    font-weight: bold;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
</style>
@endsection

@section('content')
<div class="questions-container">
    <!-- Informations du quiz -->
    <div class="quiz-info-bar">
        <h2>{{ $quiz->title }}</h2>
        <p>{{ $quiz->description }}</p>
        <div class="quiz-meta">
            <div class="quiz-meta-item">
                <i class="fas fa-layer-group"></i>
                <span>{{ $quiz->difficulty_level }}</span>
            </div>
            <div class="quiz-meta-item">
                <i class="fas fa-question-circle"></i>
                <span>{{ $questions->count() }} / {{ $quiz->nb_questions }} questions</span>
            </div>
            <div class="quiz-meta-item">
                <i class="fas fa-clock"></i>
                <span>{{ $quiz->time_limit }} minutes</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Barre d'actions -->
    <div class="action-bar">
        <div>
            <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au quiz
            </a>
        </div>
        <div>
            <a href="{{ route('admin.question.create', $quiz) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une question
            </a>
        </div>
    </div>

    <!-- Table des questions -->
    @if($questions->count() > 0)
        <table class="questions-table">
            <thead>
                <tr>
                    <th width="5%">Position</th>
                    <th width="40%">Question</th>
                    <th width="25%">Options</th>
                    <th width="10%">Réponse</th>
                    <th width="10%">Points</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                <tr>
                    <td>
                        <span class="position-badge">{{ $question->order_position }}</span>
                    </td>
                    <td>
                        <div class="question-text">
                            {{ Str::limit($question->question_text, 150) }}
                        </div>
                    </td>
                    <td>
                        <ul class="options-list">
                            <li class="{{ $question->correct_answer == 'A' ? 'correct-option' : '' }}">
                                A) {{ Str::limit($question->option_a, 50) }}
                            </li>
                            <li class="{{ $question->correct_answer == 'B' ? 'correct-option' : '' }}">
                                B) {{ Str::limit($question->option_b, 50) }}
                            </li>
                        </ul>
                    </td>
                    <td>
                        <span class="badge badge-success">{{ $question->correct_answer }}</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $question->points }} pts</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.question.edit', [$quiz, $question]) }}"
                               class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.question.destroy', [$quiz, $question]) }}"
                                  style="display: inline;"
                                  onsubmit="return confirm('Supprimer cette question ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fas fa-question-circle"></i>
            <h3>Aucune question pour ce quiz</h3>
            <p>Commencez par ajouter des questions à votre quiz</p>
            <a href="{{ route('admin.question.create', $quiz) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter la première question
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Script pour réorganiser les questions (drag & drop) - à implémenter plus tard
document.addEventListener('DOMContentLoaded', function() {
    // Animation des lignes du tableau
    const rows = document.querySelectorAll('.questions-table tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.animation = `fadeIn 0.3s ease-in-out ${index * 0.05}s forwards`;
    });
});
</script>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
