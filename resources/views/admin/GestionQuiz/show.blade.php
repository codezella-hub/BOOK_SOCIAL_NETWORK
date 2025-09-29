@extends('layouts.admin-layout')

@section('title', 'Détails du Quiz')
@section('page-title', 'Détails du Quiz')

@section('styles')
<style>
.quiz-details {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.quiz-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 30px;
    text-align: center;
}

.quiz-title {
    font-size: 2em;
    margin: 0 0 10px 0;
    font-weight: bold;
}

.quiz-subtitle {
    font-size: 1.1em;
    opacity: 0.9;
    margin: 0;
}

.quiz-content {
    padding: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.info-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.info-label {
    font-weight: bold;
    color: #495057;
    margin-bottom: 5px;
    display: block;
}

.info-value {
    font-size: 1.1em;
    color: #212529;
}

.stats-section {
    margin: 30px 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 5px;
    display: block;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.questions-section {
    margin-top: 30px;
}

.questions-list {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.question-item {
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-item:last-child {
    border-bottom: none;
}

.question-item:nth-child(even) {
    background: #f8f9fa;
}

.question-text {
    flex: 1;
    margin-right: 15px;
}

.question-meta {
    display: flex;
    gap: 15px;
    align-items: center;
    font-size: 0.9em;
    color: #6c757d;
}

.recent-attempts {
    margin-top: 30px;
}

.attempts-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.attempts-table th,
.attempts-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.attempts-table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #495057;
}

.attempts-table tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: bold;
}

.badge-success { background: #28a745; color: white; }
.badge-danger { background: #dc3545; color: white; }
.badge-warning { background: #ffc107; color: #212529; }
.badge-info { background: #17a2b8; color: white; }
.badge-secondary { background: #6c757d; color: white; }

.action-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9em;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-primary { background: #007bff; color: white; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-success { background: #28a745; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-secondary { background: #6c757d; color: white; }

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.section-title {
    font-size: 1.3em;
    font-weight: bold;
    color: #212529;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3em;
    margin-bottom: 15px;
    opacity: 0.5;
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <div class="action-buttons">
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        <a href="{{ route('admin.quiz.edit', $quiz) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier ce quiz
        </a>
        <a href="{{ route('admin.question.index', $quiz) }}" class="btn btn-success">
            <i class="fas fa-list"></i> Gérer les questions
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

    <div class="quiz-details">
        <!-- En-tête du quiz -->
        <div class="quiz-header">
            <h1 class="quiz-title">{{ $quiz->title }}</h1>
            <p class="quiz-subtitle">Livre ID: {{ $quiz->id_book }}</p>
        </div>

        <div class="quiz-content">
            <!-- Informations générales -->
            <div class="info-grid">
                <div class="info-card">
                    <span class="info-label">Description</span>
                    <div class="info-value">{{ $quiz->description }}</div>
                </div>

                <div class="info-card">
                    <span class="info-label">Niveau de difficulté</span>
                    <div class="info-value">
                        @php
                            $difficultyClass = match($quiz->difficulty_level) {
                                'beginner' => 'success',
                                'intermediate' => 'warning',
                                'advanced' => 'danger',
                                default => 'secondary'
                            };
                            $difficultyLabel = match($quiz->difficulty_level) {
                                'beginner' => 'Débutant',
                                'intermediate' => 'Intermédiaire',
                                'advanced' => 'Avancé',
                                default => $quiz->difficulty_level
                            };
                        @endphp
                        <span class="badge badge-{{ $difficultyClass }}">
                            {{ $difficultyLabel }}
                        </span>
                    </div>
                </div>

                <div class="info-card">
                    <span class="info-label">Statut</span>
                    <div class="info-value">
                        @if($quiz->is_active)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-secondary">Inactif</span>
                        @endif
                    </div>
                </div>

                <div class="info-card">
                    <span class="info-label">Paramètres</span>
                    <div class="info-value">
                        <div>⏱️ {{ $quiz->time_limit }} minute(s)</div>
                        <div>🔄 {{ $quiz->max_attempts }} tentative(s)</div>
                        <div>❓ {{ $quiz->nb_questions }} question(s)</div>
                    </div>
                </div>

                <div class="info-card">
                    <span class="info-label">Dates</span>
                    <div class="info-value">
                        <div><strong>Créé:</strong> {{ $quiz->created_at->format('d/m/Y H:i') }}</div>
                        <div><strong>Modifié:</strong> {{ $quiz->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-section">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques
                </h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-number">{{ $stats['total_questions'] }}</span>
                        <span class="stat-label">Questions</span>
                    </div>

                    <div class="stat-card">
                        <span class="stat-number">{{ $stats['total_attempts'] }}</span>
                        <span class="stat-label">Tentatives</span>
                    </div>

                    <div class="stat-card">
                        <span class="stat-number">{{ $stats['unique_participants'] }}</span>
                        <span class="stat-label">Participants</span>
                    </div>

                    <div class="stat-card">
                        <span class="stat-number">{{ number_format($stats['success_rate'], 1) }}%</span>
                        <span class="stat-label">Taux de réussite</span>
                    </div>

                    <div class="stat-card">
                        <span class="stat-number">{{ number_format($stats['average_score'], 1) }}%</span>
                        <span class="stat-label">Score moyen</span>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <div class="questions-section">
                <h2 class="section-title">
                    <i class="fas fa-question-circle"></i>
                    Questions ({{ $quiz->questions->count() }})
                </h2>

                @if($quiz->questions->count() > 0)
                    <div class="questions-list">
                        @foreach($quiz->questions as $question)
                            <div class="question-item">
                                <div class="question-text">
                                    <strong>Q{{ $loop->iteration }}:</strong>
                                    {{ Str::limit($question->question_text ?? 'Question text not available', 100) }}
                                </div>
                                <div class="question-meta">
                                    <span><i class="fas fa-star"></i> {{ $question->points ?? 1 }} pts</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-question-circle"></i>
                        <h3>Aucune question</h3>
                        <p>Ce quiz n'a pas encore de questions.</p>
                        <a href="{{ route('admin.question.index', $quiz) }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> Gérer les questions
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tentatives récentes -->
            <div class="recent-attempts">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Tentatives récentes
                </h2>

                @if($quiz->results->count() > 0)
                    <table class="attempts-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Tentative</th>
                                <th>Score</th>
                                <th>Pourcentage</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->results->sortByDesc('completed_at')->take(10) as $result)
                                <tr>
                                    <td>{{ $result->user->name ?? 'Utilisateur supprimé' }}</td>
                                    <td>{{ $result->attempt_number ?? 1 }}</td>
                                    <td>{{ $result->correct_answers ?? 0 }}/{{ $result->total_questions ?? $quiz->nb_questions }}</td>
                                    <td>{{ number_format($result->percentage ?? 0, 1) }}%</td>
                                    <td>
                                        <span class="badge badge-{{ $result->passed ? 'success' : 'danger' }}">
                                            {{ $result->passed ? 'Réussi' : 'Échoué' }}
                                        </span>
                                    </td>
                                    <td>{{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'En cours' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-line"></i>
                        <h3>Aucune tentative</h3>
                        <p>Ce quiz n'a pas encore été tenté par des utilisateurs.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation pour les cartes statistiques
    const statCards = document.querySelectorAll('.stat-card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        observer.observe(card);
    });
});
</script>
@endsection
