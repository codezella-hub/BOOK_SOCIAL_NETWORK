@extends('layouts.user-layout')

@section('title', 'Historique de mes quiz')

@section('content')
<section class="quiz-history-section py-5">
    <div class="container">
        <!-- 🎓 Titre -->
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5 text-gradient mb-2">
                <i class="fa-solid fa-trophy text-warning me-2"></i> Historique de mes Quiz
            </h1>
            <p class="lead text-muted">Analysez vos performances et suivez votre progression au fil du temps.</p>
        </div>

        <!-- 🚫 Aucun résultat -->
        @if($results->isEmpty())
            <div class="empty-state card border-0 shadow-lg p-5 text-center mx-auto" style="max-width:600px;">
                <i class="fa-solid fa-hourglass-half fa-4x text-secondary mb-3 animate-pulse"></i>
                <h3 class="fw-bold mb-2">Aucun résultat pour le moment</h3>
                <p class="text-muted mb-4">Commencez dès maintenant à relever vos premiers défis !</p>
                <a href="{{ route('user.quiz.index') }}" class="btn btn-gradient btn-lg">
                    <i class="fa-solid fa-play me-2"></i> Explorer les Quiz
                </a>
            </div>
        @else

        <!-- 📊 Statistiques globales -->
        <div class="row g-4 mb-5 text-center">
            <div class="col-md-4">
                <div class="stat-card bg-gradient-blue text-white p-4 rounded-4 shadow-lg">
                    <i class="fa-solid fa-list-check fa-2x mb-2"></i>
                    <h6 class="fw-bold text-uppercase mb-0">Quiz Passés</h6>
                    <h2 class="fw-bold mt-1">{{ $results->count() }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-gradient-purple text-white p-4 rounded-4 shadow-lg">
                    <i class="fa-solid fa-star fa-2x mb-2"></i>
                    <h6 class="fw-bold text-uppercase mb-0">Moyenne Générale</h6>
                    <h2 class="fw-bold mt-1">{{ number_format($results->avg('percentage'), 1) }} %</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-gradient-gold text-dark p-4 rounded-4 shadow-lg">
                    <i class="fa-solid fa-crown fa-2x text-warning mb-2"></i>
                    <h6 class="fw-bold text-uppercase mb-0">Réussites Totales</h6>
                    <h2 class="fw-bold mt-1">{{ $results->where('passed', true)->count() }}</h2>
                </div>
            </div>
        </div>

        <!-- 🧾 Tableau des résultats -->
   <!-- 🧾 Tableau des résultats amélioré -->
<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3 px-4">
        <h4 class="mb-0"><i class="fa-solid fa-chart-line me-2 text-warning"></i> Historique des Résultats</h4>
       <form method="GET" action="{{ route('user.quiz.history') }}" class="d-none d-md-block">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="🔍 Rechercher un quiz..."
           class="form-control form-control-sm search-bar">
</form>

    </div>

    <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0">
            <thead class="table-head">
                <tr>
                    <th class="ps-4">Quiz</th>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Réponses</th>
                    <th>Progression</th>
                    <th class="text-center">Résultat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                @php
                    $color = $result->percentage >= 80 ? 'success'
                            : ($result->percentage >= 50 ? 'warning' : 'danger');
                    $icon = $result->percentage >= 80 ? 'fa-crown'
                            : ($result->percentage >= 50 ? 'fa-thumbs-up' : 'fa-face-frown');
                @endphp
                <tr class="quiz-row">
                    <td class="ps-4">
                        <div class="fw-semibold text-dark">{{ $result->quiz->title ?? 'Quiz supprimé' }}</div>
                        <small class="text-muted d-flex align-items-center mt-1">
                            <i class="fa-regular fa-clock me-1"></i> Tentative #{{ $result->attempt_number }}
                        </small>
                    </td>
                    <td>
                        <i class="fa-regular fa-calendar-days me-1 text-muted"></i>
                        {{ $result->completed_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <span class="fw-bold text-{{ $color }}">
                            <i class="fa-solid fa-chart-pie me-1"></i>
                            {{ number_format($result->percentage, 1) }} %
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold text-secondary">
                            {{ $result->correct_answers }}/{{ $result->total_questions }}
                        </span>
                    </td>
                    <td style="min-width:180px;">
                        <div class="progress bg-light-subtle" style="height: 10px;">
                            <div class="progress-bar bg-{{ $color }}"
                                 role="progressbar"
                                 style="width: {{ $result->percentage }}%; transition: width 0.8s ease;"
                                 aria-valuenow="{{ $result->percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($result->passed)
                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-check me-1"></i> Réussi
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-xmark me-1"></i> Échoué
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-light text-center py-3">
        {{ $results->links('pagination::bootstrap-5') }}
    </div>
</div>

        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
/* ==== BACKGROUND ==== */
.quiz-history-section {
    background: linear-gradient(180deg, #f3f4f6 0%, #ffffff 100%);
}

/* ==== TITLE GRADIENT ==== */
.text-gradient {
    background: linear-gradient(90deg, #007bff, #6610f2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ==== STATS ==== */
.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.bg-gradient-blue {
    background: linear-gradient(135deg, #3b82f6, #1e3a8a);
}
.bg-gradient-purple {
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
}
.bg-gradient-gold {
    background: linear-gradient(135deg, #fbbf24, #b45309);
}

/* ==== TABLE ==== */
.table {
    border-collapse: separate;
    border-spacing: 0 0.5rem;
}
.table-head {
    background-color: #f1f5f9;
    font-weight: 700;
    color: #111827;
    text-transform: uppercase;
}
.table-row {
    background-color: #ffffff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.table-row:hover {
    transform: scale(1.01);
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

/* ==== BADGES ==== */
.score-badge {
    font-size: 0.95rem;
    padding: 0.4rem 0.8rem;
}

/* ==== PROGRESS BAR ==== */
.progress {
    background-color: #e5e7eb;
    border-radius: 6px;
    overflow: hidden;
}
.progress-bar {
    transition: width 0.6s ease-in-out;
}

/* ==== SEARCH BAR ==== */
.search-bar {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6;
    border-radius: 25px;
    padding: 0.4rem 1rem;
    width: 220px;
}

/* ==== ANIMATIONS ==== */
.animate-fade {
    animation: fadeIn 1.2s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95);}
    to { opacity: 1; transform: scale(1);}
}

.animate-pulse {
    animation: pulse 1.8s infinite;
}
@keyframes pulse {
    0%,100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.1); opacity: 1; }
}

/* ==== BUTTON ==== */
.btn-gradient {
    background: linear-gradient(135deg, #2563eb, #06b6d4);
    color: #fff;
    border: none;
    transition: 0.3s ease;
}
.btn-gradient:hover {
    background: linear-gradient(135deg, #06b6d4, #2563eb);
    transform: translateY(-3px);
}
</style>
@endpush
