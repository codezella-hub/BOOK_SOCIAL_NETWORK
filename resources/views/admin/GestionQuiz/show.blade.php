@extends('layouts.admin-layout')

@section('title', 'Détails du Quiz')
@section('page-title', 'Détails du Quiz')

@section('styles')
<style>
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.btn-group-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9em;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-sm {
    padding: 6px 14px;
    font-size: 0.85em;
}

.quiz-header-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.quiz-header-card h1 {
    color: #212529;
    font-size: 1.8em;
    margin-bottom: 15px;
}

.quiz-header-card p {
    color: #6c757d;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.content-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 25px;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}

.card-header h5 {
    margin: 0;
    color: #212529;
    font-size: 1.1em;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-body {
    padding: 25px;
}

.info-row {
    margin-bottom: 20px;
}

.info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: block;
}

.info-value {
    color: #6c757d;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.badge {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.9em;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #212529;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.badge-info {
    background: #17a2b8;
    color: white;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-pill {
    border-radius: 20px;
}

.params-list {
    font-size: 0.9em;
}

.params-list div {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.divider {
    border: none;
    border-top: 1px solid #e9ecef;
    margin: 20px 0;
}

.date-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.question-list-item {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-list-item:last-child {
    border-bottom: none;
}

.question-text {
    color: #495057;
}

.question-number {
    font-weight: 600;
    color: #007bff;
    margin-right: 8px;
}

.empty-state {
    text-align: center;
    padding: 50px 30px;
}

.empty-state-icon {
    font-size: 3.5em;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 20px;
}

.attempts-table {
    width: 100%;
    border-collapse: collapse;
}

.attempts-table thead {
    background: #f8f9fa;
}

.attempts-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    font-size: 0.85em;
    border-bottom: 2px solid #dee2e6;
}

.attempts-table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    font-size: 0.9em;
}

.attempts-table tbody tr:hover {
    background-color: #f8f9fa;
}

.stats-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.stats-header {
    background: #007bff;
    color: white;
    padding: 20px 25px;
}

.stats-header h5 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-body {
    padding: 25px;
}

.main-stat {
    text-align: center;
    margin-bottom: 30px;
}

.main-stat-number {
    font-size: 3em;
    font-weight: bold;
    color: #007bff;
}

.main-stat-label {
    color: #6c757d;
    font-size: 0.95em;
    margin-top: 5px;
}

.sub-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    text-align: center;
    margin-bottom: 30px;
}

.sub-stat-number {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 5px;
}

.sub-stat-number.info {
    color: #17a2b8;
}

.sub-stat-number.success {
    color: #28a745;
}

.sub-stat-label {
    color: #6c757d;
    font-size: 0.85em;
}

.stat-item {
    margin-bottom: 25px;
}

.stat-item:last-child {
    margin-bottom: 0;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.stat-label {
    color: #6c757d;
}

.stat-value {
    font-size: 1.3em;
    font-weight: bold;
}

.stat-value.success {
    color: #28a745;
}

.stat-value.warning {
    color: #ffc107;
}

.stat-value.danger {
    color: #dc3545;
}

.stat-value.primary {
    color: #007bff;
}

.progress {
    height: 20px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-bar.bg-success {
    background: #28a745;
}

.progress-bar.bg-warning {
    background: #ffc107;
}

.progress-bar.bg-danger {
    background: #dc3545;
}

.progress-bar.bg-primary {
    background: #007bff;
}

@media (max-width: 992px) {
    .content-layout {
        grid-template-columns: 1fr;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .btn-group-actions {
        flex-direction: column;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .date-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <!-- Boutons d'action -->
<div class="btn-group-actions">
    <a href="{{ route('admin.quiz.edit', $quiz->id_quiz) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Modifier
    </a>

    <a href="{{ route('admin.quiz.question.index', $quiz->id_quiz) }}" class="btn btn-success">
        <i class="fas fa-tasks"></i> Gérer les Questions
    </a>

    <a href="{{ route('admin.quiz.question.generate.form', $quiz->id_quiz) }}" class="btn btn-primary">
        <i class="fas fa-robot"></i> Générer via IA
    </a>

    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
        <i class="fas fa-trash"></i> Supprimer
    </button>
</div>

    </div>

    <!-- Formulaire de suppression caché -->
    <form id="delete-form" method="POST" action="{{ route('admin.quiz.destroy', $quiz->id_quiz) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- En-tête du quiz -->
    <div class="quiz-header-card">
        <h1>{{ $quiz->title }}</h1>
        <p>
            <i class="fas fa-book"></i>
            <strong>Livre:</strong> {{ $quiz->book->title ?? 'Livre inconnu' }}
            @if($quiz->book && $quiz->book->author_name)
                par <em>{{ $quiz->book->author_name }}</em>
            @endif
        </p>
    </div>

    <div class="content-layout">
        <!-- Colonne gauche: Informations -->
        <div>
            <!-- Informations générales -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <label class="info-label">Description</label>
                        <p class="info-value">{{ $quiz->description }}</p>
                    </div>

                    <div class="info-grid">
                        <div>
                            <label class="info-label">Difficulté</label>
                            @php
                                $difficultyConfig = [
                                    'beginner' => ['label' => 'Débutant', 'color' => 'success', 'icon' => 'fa-star'],
                                    'intermediate' => ['label' => 'Intermédiaire', 'color' => 'warning', 'icon' => 'fa-star-half-alt'],
                                    'advanced' => ['label' => 'Avancé', 'color' => 'danger', 'icon' => 'fa-fire']
                                ];
                                $config = $difficultyConfig[$quiz->difficulty_level] ?? ['label' => 'Inconnu', 'color' => 'secondary', 'icon' => 'fa-question'];
                            @endphp
                            <div>
                                <span class="badge badge-{{ $config['color'] }}">
                                    <i class="fas {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="info-label">Statut</label>
                            <div>
                                <span class="badge badge-{{ $quiz->is_active ? 'success' : 'secondary' }}">
                                    <i class="fas fa-{{ $quiz->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ $quiz->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="info-label">Paramètres</label>
                            <div class="params-list">
                                <div><i class="fas fa-clock" style="color: #007bff;"></i> {{ $quiz->time_limit }} minutes</div>
                                <div><i class="fas fa-redo" style="color: #17a2b8;"></i> {{ $quiz->max_attempts }} tentative(s)</div>
                                <div><i class="fas fa-question-circle" style="color: #28a745;"></i> {{ $quiz->nb_questions }} question(s)</div>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="date-grid">
                        <div>
                            <label class="info-label">Créé le</label>
                            <p class="info-value">{{ $quiz->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="info-label">Dernière modification</label>
                            <p class="info-value">{{ $quiz->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <h5>
                            <i class="fas fa-question-circle"></i>
                            Questions ({{ $quiz->questions->count() }})
                        </h5>
                        <a href="{{ route('admin.quiz.question.index', $quiz->id_quiz) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une question
                        </a>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($quiz->questions->count() > 0)
                        <div>
                            @foreach($quiz->questions as $question)
                                <div class="question-list-item">
                                    <div>
                                        <span class="question-number">Q{{ $loop->iteration }}:</span>
                                        <span class="question-text">{{ Str::limit($question->question_text ?? 'Question text not available', 80) }}</span>
                                    </div>
                                    <span class="badge badge-info badge-pill">{{ $question->points ?? 1 }} pts</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h5>Aucune question</h5>
                            <p>Ce quiz n'a pas encore de questions.</p>
                            <a href="{{ route('admin.quiz.question.index', $quiz->id_quiz) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter des questions
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tentatives récentes -->
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-history"></i>
                        Tentatives récentes
                    </h5>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($quiz->results->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="attempts-table">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th style="text-align: center;">Tentative</th>
                                        <th style="text-align: center;">Score</th>
                                        <th style="text-align: center;">Pourcentage</th>
                                        <th style="text-align: center;">Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quiz->results->sortByDesc('completed_at')->take(10) as $result)
                                        <tr>
                                            <td>
                                                <i class="fas fa-user" style="color: #6c757d;"></i>
                                                {{ $result->user->name ?? 'Utilisateur supprimé' }}
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-secondary">{{ $result->attempt_number ?? 1 }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                {{ $result->correct_answers ?? 0 }}/{{ $result->total_questions ?? $quiz->nb_questions }}
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>{{ number_format($result->percentage ?? 0, 1) }}%</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-{{ $result->passed ? 'success' : 'danger' }}">
                                                    {{ $result->passed ? 'Réussi' : 'Échoué' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'En cours' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5>Aucune tentative</h5>
                            <p>Ce quiz n'a pas encore été tenté par des utilisateurs.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite: Statistiques -->
        <div>
            <div class="stats-card">
                <div class="stats-header">
                    <h5>
                        <i class="fas fa-chart-bar"></i>
                        Statistiques
                    </h5>
                </div>
                <div class="stats-body">
                    <div class="main-stat">
                        <div class="main-stat-number">{{ $stats['total_questions'] }}</div>
                        <div class="main-stat-label">Questions</div>
                    </div>

                    <hr class="divider">

                    <div class="sub-stats">
                        <div>
                            <div class="sub-stat-number info">{{ $stats['total_attempts'] }}</div>
                            <div class="sub-stat-label">Tentatives</div>
                        </div>
                        <div>
                            <div class="sub-stat-number success">{{ $stats['unique_participants'] }}</div>
                            <div class="sub-stat-label">Participants</div>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="stat-item">
                        <div class="stat-row">
                            <span class="stat-label">Taux de réussite</span>
                            <span class="stat-value {{ $stats['success_rate'] >= 70 ? 'success' : ($stats['success_rate'] >= 40 ? 'warning' : 'danger') }}">
                                {{ number_format($stats['success_rate'], 1) }}%
                            </span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $stats['success_rate'] >= 70 ? 'success' : ($stats['success_rate'] >= 40 ? 'warning' : 'danger') }}"
                                 style="width: {{ $stats['success_rate'] }}%">
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-row">
                            <span class="stat-label">Score moyen</span>
                            <span class="stat-value primary">{{ number_format($stats['average_score'], 1) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary"
                                 style="width: {{ $stats['average_score'] }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?\n\nToutes les questions et résultats associés seront également supprimés.\n\nCette action est irréversible.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endsection
