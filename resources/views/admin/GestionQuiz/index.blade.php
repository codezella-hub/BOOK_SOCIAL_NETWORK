@extends('layouts.admin-layout')

@section('title', 'Gestion des Quiz')
@section('page-title', 'Gestion des Quiz')

@section('styles')
<style>
.quiz-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #007bff;
}

.quiz-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 15px;
}

.quiz-title {
    font-size: 1.2em;
    font-weight: bold;
    color: #333;
    margin: 0;
}

.quiz-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 0.9em;
    color: #666;
}

.quiz-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
    min-width: 80px;
}

.stat-number {
    font-size: 1.2em;
    font-weight: bold;
    color: #007bff;
}

.stat-label {
    font-size: 0.8em;
    color: #666;
}

.quiz-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9em;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; color: white; }
.btn-info { background: #17a2b8; color: white; }

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: bold;
}

.badge-success { background: #28a745; color: white; }
.badge-warning { background: #ffc107; color: #212529; }
.badge-danger { background: #dc3545; color: white; }
.badge-secondary { background: #6c757d; color: white; }

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.filters {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <div class="page-header">
        <h2>Gestion des Quiz</h2>
      <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Quiz
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="filters">
        <select id="statusFilter" class="form-control">
            <option value="">Tous les statuts</option>
            <option value="active">Actifs</option>
            <option value="inactive">Inactifs</option>
        </select>

        <select id="difficultyFilter" class="form-control">
            <option value="">Toutes les difficultés</option>
            <option value="beginner">Débutant</option>
            <option value="intermediate">Intermédiaire</option>
            <option value="advanced">Avancé</option>
        </select>

        <input type="text" id="searchInput" placeholder="Rechercher un quiz..." class="form-control">
    </div>

    <div class="quiz-list">
        @forelse($quizzes as $quiz)
            <div class="quiz-card" data-status="{{ $quiz->is_active ? 'active' : 'inactive' }}" data-difficulty="{{ $quiz->difficulty_level }}">
                <div class="quiz-header">
                    <h3 class="quiz-title">{{ $quiz->title }}</h3>
                    <div>
                        {!! $quiz->is_active
                            ? '<span class="badge badge-success">Actif</span>'
                            : '<span class="badge badge-secondary">Inactif</span>' !!}
                    </div>
                </div>

                <div class="quiz-meta">
                    <span><i class="fas fa-book"></i> {{ $quiz->book_name }}</span>
                    <span><i class="fas fa-signal"></i> {{ $quiz->difficulty_label }}</span>
                    <span><i class="fas fa-clock"></i> {{ $quiz->formatted_time_limit }}</span>
                    <span><i class="fas fa-redo"></i> {{ $quiz->max_attempts }} tentative(s)</span>
                </div>

                <p class="quiz-description">{{ Str::limit($quiz->description, 150) }}</p>

                <div class="quiz-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ $quiz->stats['total_questions'] }}</div>
                        <div class="stat-label">Questions</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $quiz->stats['total_attempts'] }}</div>
                        <div class="stat-label">Tentatives</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $quiz->stats['success_rate'] }}%</div>
                        <div class="stat-label">Réussite</div>
                    </div>
                </div>

                <div class="quiz-actions">
       <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                 <a href="{{ route('admin.quiz.edit', $quiz) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
<form method="POST" action="{{ route('admin.quiz.destroy', $quiz) }}" style="display: inline;"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="quiz-card">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-question-circle" style="font-size: 3em; color: #ccc; margin-bottom: 15px;"></i>
                    <h3>Aucun quiz trouvé</h3>
                    <p>Commencez par créer votre premier quiz.</p>
 <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer un Quiz
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{ $quizzes->links() }}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const difficultyFilter = document.getElementById('difficultyFilter');
    const searchInput = document.getElementById('searchInput');
    const quizCards = document.querySelectorAll('.quiz-card');

    function filterQuizzes() {
        const statusValue = statusFilter.value;
        const difficultyValue = difficultyFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        quizCards.forEach(card => {
            const cardStatus = card.dataset.status;
            const cardDifficulty = card.dataset.difficulty;
            const cardText = card.textContent.toLowerCase();

            const statusMatch = !statusValue || cardStatus === statusValue;
            const difficultyMatch = !difficultyValue || cardDifficulty === difficultyValue;
            const searchMatch = !searchValue || cardText.includes(searchValue);

            if (statusMatch && difficultyMatch && searchMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    statusFilter.addEventListener('change', filterQuizzes);
    difficultyFilter.addEventListener('change', filterQuizzes);
    searchInput.addEventListener('input', filterQuizzes);
});
</script>
@endsection
