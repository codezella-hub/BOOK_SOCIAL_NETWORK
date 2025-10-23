@extends('layouts.admin-layout')

@section('title', 'Gestion des Questions')
@section('page-title', 'Questions du Quiz: ' . $quiz->title)

@section('styles')
<style>
.quiz-header {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.quiz-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.quiz-info h2 {
    color: #212529;
    font-size: 1.8em;
    margin-bottom: 10px;
}

.quiz-info p {
    color: #6c757d;
    margin-bottom: 15px;
}

.quiz-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.quiz-badge {
    background: #e7f3ff;
    color: #0056b3;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9em;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.book-info {
    text-align: right;
}

.book-badge {
    background: #f8f9fa;
    padding: 10px 20px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    display: inline-block;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 15px;
}

.btn-group-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 8px;
}

.stat-number.primary { color: #007bff; }
.stat-number.info { color: #17a2b8; }
.stat-number.success { color: #28a745; }
.stat-number.warning { color: #ffc107; }
.stat-number.danger { color: #dc3545; }

.stat-label {
    color: #6c757d;
    font-size: 0.9em;
}

.stat-progress {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-top: 10px;
    overflow: hidden;
}

.stat-progress-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s ease;
}

.questions-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.questions-header {
    padding: 20px 30px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.questions-header h5 {
    margin: 0;
    color: #212529;
    display: flex;
    align-items: center;
    gap: 10px;
}

.questions-table {
    width: 100%;
    border-collapse: collapse;
}

.questions-table thead {
    background: #f8f9fa;
}

.questions-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    font-size: 0.9em;
    border-bottom: 2px solid #dee2e6;
}

.questions-table td {
    padding: 20px 15px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.questions-table tbody tr {
    transition: background-color 0.2s ease;
}

.questions-table tbody tr:hover {
    background-color: #f8f9fa;
}

.question-number {
    background: #6c757d;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85em;
}

.question-text {
    font-weight: 600;
    color: #212529;
    margin-bottom: 10px;
    line-height: 1.5;
}

.options-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.option-badge {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.8em;
    color: #495057;
}

.correct-answer-badge {
    background: #28a745;
    color: white;
    padding: 6px 16px;
    border-radius: 6px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.points-badge {
    background: #17a2b8;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 500;
}

.position-badge {
    background: #6c757d;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 500;
}

.actions-buttons {
    display: flex;
    gap: 6px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9em;
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

.btn-outline-secondary {
    background: white;
    color: #6c757d;
    border: 1px solid #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.btn-outline-info {
    background: white;
    color: #17a2b8;
    border: 1px solid #17a2b8;
}

.btn-outline-info:hover {
    background: #17a2b8;
    color: white;
}

.btn-outline-warning {
    background: white;
    color: #ffc107;
    border: 1px solid #ffc107;
}

.btn-outline-warning:hover {
    background: #ffc107;
    color: #212529;
}

.btn-outline-danger {
    background: white;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85em;
}

.empty-state {
    text-align: center;
    padding: 80px 40px;
}

.empty-state-icon {
    font-size: 5em;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #6c757d;
    margin-bottom: 15px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 25px;
}

.alert {
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-dismissible {
    position: relative;
    padding-right: 50px;
}

.btn-close {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    font-size: 1.2em;
    cursor: pointer;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}

.info-tip {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    padding: 15px 30px;
    color: #6c757d;
    font-size: 0.9em;
    display: flex;
    align-items: center;
    gap: 10px;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quiz-header-top {
        flex-direction: column;
        gap: 20px;
    }

    .book-info {
        text-align: left;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-group-actions {
        flex-direction: column;
    }

    .questions-table {
        font-size: 0.85em;
    }

    .questions-table th,
    .questions-table td {
        padding: 10px 8px;
    }

    .stat-number {
        font-size: 2em;
    }
}

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

.questions-table tbody tr {
    animation: fadeIn 0.3s ease-in-out;
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <!-- Messages de feedback -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Succès!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Erreur!</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- En-tête avec informations du quiz -->
    <div class="quiz-header">
        <div class="quiz-header-top">
            <div class="quiz-info">
                <h2>{{ $quiz->title }}</h2>
                <p>{{ $quiz->description }}</p>
                <div class="quiz-badges">
                    <span class="quiz-badge">
                        <i class="fas fa-layer-group"></i>
                        {{ ucfirst($quiz->difficulty_level) }}
                    </span>
                    <span class="quiz-badge">
                        <i class="fas fa-clock"></i>
                        {{ $quiz->time_limit }} minutes
                    </span>
                    <span class="quiz-badge">
                        <i class="fas fa-redo"></i>
                        {{ $quiz->max_attempts }} tentatives max
                    </span>
                </div>
            </div>
            @if($quiz->book)
            <div class="book-info">
                <div class="book-badge">
                    <div style="font-weight: 600; margin-bottom: 5px;">
                        <i class="fas fa-book"></i>
                        {{ $quiz->book->title }}
                    </div>
                    @if($quiz->book->author_name)
                    <div style="font-size: 0.9em; color: #6c757d;">
                        <i class="fas fa-user"></i>
                        {{ $quiz->book->author_name }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Barre d'actions -->
   <div class="action-bar">
    <div class="btn-group-actions">
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Liste des quiz
        </a>

        @if($quiz->book)
            <a href="{{ route('admin.quiz.show', [$quiz->id_book, $quiz->id_quiz]) }}" class="btn btn-outline-info">
                <i class="fas fa-eye"></i> Voir le quiz
            </a>
        @endif

        <a href="{{ route('admin.quiz.edit', $quiz->id_quiz) }}" class="btn btn-outline-warning">
            <i class="fas fa-edit"></i> Modifier le quiz
        </a>
    </div>

    <a href="{{ route('admin.quiz.question.create', $quiz->id_quiz) }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter une question
    </a>
</div>


    <!-- Statistiques du quiz -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number primary">{{ $questions->count() }}</div>
            <div class="stat-label">Questions créées</div>
            <div class="stat-progress">
                <div class="stat-progress-bar" style="width: 100%; background: #007bff;"></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-number info">{{ $quiz->nb_questions }}</div>
            <div class="stat-label">Questions requises</div>
            <div class="stat-progress">
                <div class="stat-progress-bar" style="width: 100%; background: #17a2b8;"></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-number success">{{ number_format($questions->sum('points'), 1) }}</div>
            <div class="stat-label">Points totaux</div>
            <div class="stat-progress">
                <div class="stat-progress-bar" style="width: 100%; background: #28a745;"></div>
            </div>
        </div>
        <div class="stat-card">
            @php
                $progress = $quiz->nb_questions > 0 ? round(($questions->count() / $quiz->nb_questions) * 100) : 0;
                $progressColor = $progress >= 100 ? 'success' : ($progress >= 50 ? 'warning' : 'danger');
                $progressColorHex = $progress >= 100 ? '#28a745' : ($progress >= 50 ? '#ffc107' : '#dc3545');
            @endphp
            <div class="stat-number {{ $progressColor }}">{{ $progress }}%</div>
            <div class="stat-label">Progression</div>
            <div class="stat-progress">
                <div class="stat-progress-bar" style="width: {{ $progress }}%; background: {{ $progressColorHex }};"></div>
            </div>
        </div>
    </div>

    <!-- Liste des questions -->
    <div class="questions-container">
        <div class="questions-header">
            <h5>
                <i class="fas fa-list-ul" style="color: #007bff;"></i>
                Questions du quiz ({{ $questions->count() }})
            </h5>
            @if($questions->count() > 0)
            <span style="background: #f8f9fa; padding: 6px 16px; border-radius: 20px; font-size: 0.9em; color: #495057;">
                Total: {{ $questions->count() }} question(s)
            </span>
            @endif
        </div>

        @if($questions->count() > 0)
            <table class="questions-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">#</th>
                        <th style="width: 45%;">Question</th>
                        <th style="width: 15%; text-align: center;">Réponse correcte</th>
                        <th style="width: 10%; text-align: center;">Points</th>
                        <th style="width: 10%; text-align: center;">Position</th>
                        <th style="width: 15%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                        <tr>
                            <td style="text-align: center;">
                                <span class="question-number">Q{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="question-text">
                                    {{ Str::limit($question->question_text, 100) }}
                                </div>
                                <div class="options-badges">
                                    <span class="option-badge">
                                        <strong>A:</strong> {{ Str::limit($question->option_a, 25) }}
                                    </span>
                                    <span class="option-badge">
                                        <strong>B:</strong> {{ Str::limit($question->option_b, 25) }}
                                    </span>
                                    <span class="option-badge">
                                        <strong>C:</strong> {{ Str::limit($question->option_c, 25) }}
                                    </span>
                                    <span class="option-badge">
                                        <strong>D:</strong> {{ Str::limit($question->option_d, 25) }}
                                    </span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="correct-answer-badge">
                                    <i class="fas fa-check"></i>
                                    Option {{ $question->correct_answer }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="points-badge">
                                    {{ number_format($question->points, 1) }} pts
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="position-badge">
                                    #{{ $question->order_position }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="actions-buttons">

                                    <a href="{{ route('admin.quiz.question.edit', ['quiz' => $quiz->id_quiz, 'question' => $question->id]) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            onclick="confirmDelete({{ $question->id }}, '{{ addslashes(Str::limit($question->question_text, 50)) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Formulaire de suppression caché -->
                                <form id="delete-form-{{ $question->id }}"
                                      method="POST"
                                      action="{{ route('admin.quiz.question.destroy', ['quiz' => $quiz->id_quiz, 'question' => $question->id]) }}"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Info drag & drop -->
            <div class="info-tip">
                <i class="fas fa-info-circle" style="color: #17a2b8;"></i>
                <span>Astuce : La fonctionnalité de réorganisation par glisser-déposer sera disponible prochainement.</span>
            </div>
        @else
            <!-- État vide -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h4>Aucune question pour ce quiz</h4>
                <p>
                    Commencez par ajouter des questions pour compléter votre quiz.
                    <br>
                    <span style="background: #17a2b8; color: white; padding: 6px 16px; border-radius: 6px; display: inline-block; margin-top: 10px;">
                        Objectif : {{ $quiz->nb_questions }} questions
                    </span>
                </p>
                <a href="{{ route('admin.quiz.question.create', $quiz->id_quiz) }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 1em;">
                    <i class="fas fa-plus"></i> Ajouter la première question
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'apparition des lignes
        const rows = document.querySelectorAll('.questions-table tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    });

    // Confirmation de suppression améliorée
    function confirmDelete(questionId, questionText) {
        const confirmMessage = `Êtes-vous sûr de vouloir supprimer cette question ?\n\n"${questionText}"\n\nCette action est irréversible.`;

        if (confirm(confirmMessage)) {
            document.getElementById('delete-form-' + questionId).submit();
        }
    }

    // Auto-fermeture des alertes après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    });
</script>
@endsection
