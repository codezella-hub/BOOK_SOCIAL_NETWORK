@extends('layouts.admin-layout')

@section('title', 'Gestion des Quiz')
@section('page-title', 'Gestion des Quiz')

@section('styles')
<style>
body {
    background-color: #f4f6f9;
    font-family: "Inter", sans-serif;
}
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.dashboard-header h1 {
    font-weight: 700;
    color: #1e293b;
}
.stats-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}
.stat-card {
    flex: 1;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    text-align: center;
    padding: 20px;
}
.stat-card h3 {
    font-size: 2rem;
    color: #2563eb;
    margin: 0;
}
.stat-card p {
    color: #64748b;
    margin-top: 5px;
    font-weight: 500;
}
.table thead {
    background: #2563eb;
    color: white;
    text-align: center;
}
.table td, .table th {
    vertical-align: middle;
    text-align: center;
}
.badge {
    font-size: .85em;
}
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1><i class="fas fa-graduation-cap"></i> Gestion des Quiz</h1>
    <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Quiz
    </a>
</div>

<!-- 📊 Statistiques globales -->
<div class="stats-cards">
    <div class="stat-card"><h3>{{ $globalStats['total_quizzes'] }}</h3><p>Total des Quiz</p></div>
    <div class="stat-card"><h3>{{ $globalStats['active_quizzes'] }}</h3><p>Quiz Actifs</p></div>
    <div class="stat-card"><h3>{{ $globalStats['avg_success_rate'] }}%</h3><p>Taux de Réussite Moyen</p></div>
</div>

<!-- 🧾 Tableau principal -->
<div class="card shadow-sm p-3 bg-white">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Livre</th>
                    <th>Difficulté</th>
                    <th>Questions</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->id_quiz }}</td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->book->title ?? '—' }}</td>
                        <td>
                            @switch($quiz->difficulty_level)
                                @case('beginner')
                                    <span class="badge bg-success">Débutant</span>
                                    @break
                                @case('intermediate')
                                    <span class="badge bg-warning text-dark">Intermédiaire</span>
                                    @break
                                @case('advanced')
                                    <span class="badge bg-danger">Avancé</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Inconnu</span>
                            @endswitch
                        </td>
                        <td>{{ $quiz->questions->count() }}</td>
                        <td>
                            @if($quiz->is_active)
                                <span class="badge bg-primary">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $quiz->created_at->format('d/m/Y') }}</td>
                        <td>
                            <!-- 👁️ Voir détails -->
                            <a href="{{ route('admin.quiz.show', ['book' => $quiz->id_book, 'quiz' => $quiz->id_quiz]) }}"
                               class="btn btn-sm btn-outline-info" title="Voir le quiz">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- ✏️ Modifier -->
                            <a href="{{ route('admin.quiz.edit', $quiz->id_quiz) }}"
                               class="btn btn-sm btn-outline-primary" title="Modifier le quiz">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- 🗑 Supprimer -->
                            <form action="{{ route('admin.quiz.destroy', $quiz->id_quiz) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce quiz ?')"
                                        title="Supprimer le quiz">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Aucun quiz trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
