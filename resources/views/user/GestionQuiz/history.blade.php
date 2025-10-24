@extends('layouts.user-layout')

@section('title', 'Historique de mes quiz')

@section('styles')
    <style>
        /* ==== VARIABLES CSS ==== */
        :root {
            --primary-blue: #3b82f6;
            --primary-purple: #8b5cf6;
            --dark-blue: #1e3a8a;
            --dark-purple: #6d28d9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gold: #fbbf24;
            --dark-gold: #b45309;
            --dark: #111827;
            --light: #f8fafc;
            --muted: #6b7280;
        }

        /* ==== RESET ET BASE ==== */
        .quiz-history-section * {
            box-sizing: border-box;
        }

        /* ==== SECTION PRINCIPALE ==== */
        .quiz-history-section {
            background: linear-gradient(180deg, #f3f4f6 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .quiz-history-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ==== TITRE ==== */
        .quiz-history-section .text-center {
            text-align: center;
            margin-bottom: 3rem;
        }

        .quiz-history-section .display-5 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .quiz-history-section .lead {
            font-size: 1.25rem;
            color: var(--muted);
            font-weight: 300;
        }

        /* ==== ÉTAT VIDE ==== */
        .quiz-history-section .empty-state {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 3rem 2rem;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
            border: none;
        }

        .quiz-history-section .empty-state .fa-4x {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #9ca3af;
        }

        .quiz-history-section .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .quiz-history-section .empty-state p {
            color: var(--muted);
            margin-bottom: 2rem;
        }

        /* ==== BOUTON GRADIENT ==== */
        .quiz-history-section .btn-gradient {
            background: linear-gradient(135deg, var(--primary-blue), #06b6d4);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .quiz-history-section .btn-gradient:hover {
            background: linear-gradient(135deg, #06b6d4, var(--primary-blue));
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: white;
        }

        /* ==== STATISTIQUES ==== */
        .quiz-history-section .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem 3rem -0.5rem;
        }

        .quiz-history-section .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 0.5rem;
        }

        @media (max-width: 768px) {
            .quiz-history-section .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }
        }

        .quiz-history-section .stat-card {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 2rem 1rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .quiz-history-section .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .quiz-history-section .stat-card .fa-2x {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .quiz-history-section .stat-card h6 {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            opacity: 0.9;
        }

        .quiz-history-section .stat-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0 0 0;
        }

        /* Couleurs des cartes de stats */
        .quiz-history-section .bg-gradient-blue {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        }

        .quiz-history-section .bg-gradient-purple {
            background: linear-gradient(135deg, var(--primary-purple), var(--dark-purple));
        }

        .quiz-history-section .bg-gradient-gold {
            background: linear-gradient(135deg, var(--gold), var(--dark-gold));
            color: var(--dark);
        }

        .quiz-history-section .bg-gradient-gold .text-warning {
            color: var(--dark) !important;
        }

        /* ==== CARTE PRINCIPALE ==== */
        .quiz-history-section .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .quiz-history-section .card-header {
            background: var(--dark);
            color: white;
            padding: 1rem 1.5rem;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quiz-history-section .card-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .quiz-history-section .card-header .text-warning {
            color: var(--gold) !important;
        }

        /* ==== BARRE DE RECHERCHE ==== */
        .quiz-history-section .search-bar {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 0.4rem 1rem;
            width: 220px;
            color: white;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .quiz-history-section .search-bar::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .quiz-history-section .search-bar:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        }

        /* ==== TABLEAU ==== */
        .quiz-history-section .table-responsive {
            overflow-x: auto;
        }

        .quiz-history-section .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            margin: 0;
        }

        .quiz-history-section .table-head {
            background-color: #f1f5f9;
        }

        .quiz-history-section .table-head tr th {
            padding: 1rem 0.75rem;
            font-weight: 700;
            color: var(--dark);
            text-transform: uppercase;
            font-size: 0.875rem;
            border: none;
            text-align: left;
        }

        .quiz-history-section .table-head tr th:first-child {
            padding-left: 1.5rem;
        }

        .quiz-history-section .table-head tr th.text-center {
            text-align: center;
        }

        /* ==== LIGNES DU TABLEAU ==== */
        .quiz-history-section .quiz-row {
            background-color: white;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .quiz-history-section .quiz-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .quiz-history-section .quiz-row td {
            padding: 1.25rem 0.75rem;
            border: none;
            vertical-align: middle;
        }

        .quiz-history-section .quiz-row td:first-child {
            padding-left: 1.5rem;
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .quiz-history-section .quiz-row td:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .quiz-history-section .fw-semibold {
            font-weight: 600;
            color: var(--dark);
        }

        .quiz-history-section .text-muted {
            color: var(--muted) !important;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            margin-top: 0.25rem;
        }

        /* ==== COULEURS DES SCORES ==== */
        .quiz-history-section .text-success {
            color: var(--success) !important;
        }

        .quiz-history-section .text-warning {
            color: var(--warning) !important;
        }

        .quiz-history-section .text-danger {
            color: var(--danger) !important;
        }

        .quiz-history-section .text-secondary {
            color: var(--muted) !important;
        }

        /* ==== BARRE DE PROGRESSION ==== */
        .quiz-history-section .progress {
            background-color: #e5e7eb;
            border-radius: 6px;
            height: 10px;
            overflow: hidden;
            min-width: 180px;
        }

        .quiz-history-section .progress-bar {
            height: 100%;
            border-radius: 6px;
            transition: width 0.8s ease;
        }

        .quiz-history-section .bg-success {
            background-color: var(--success) !important;
        }

        .quiz-history-section .bg-warning {
            background-color: var(--warning) !important;
        }

        .quiz-history-section .bg-danger {
            background-color: var(--danger) !important;
        }

        .quiz-history-section .bg-light-subtle {
            background-color: #f8f9fa !important;
        }

        /* ==== BADGES ==== */
        .quiz-history-section .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
        }

        .quiz-history-section .bg-success {
            background: linear-gradient(135deg, var(--success), #059669) !important;
        }

        .quiz-history-section .bg-danger {
            background: linear-gradient(135deg, var(--danger), #dc2626) !important;
        }

        /* ==== PAGINATION ==== */
        .quiz-history-section .card-footer {
            background-color: var(--light);
            border: none;
            padding: 1rem;
            text-align: center;
        }

        .quiz-history-section .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.25rem;
        }

        .quiz-history-section .page-item .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            color: var(--primary-blue);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .quiz-history-section .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            border-color: var(--primary-blue);
            color: white;
        }

        .quiz-history-section .page-item .page-link:hover {
            background-color: #f3f4f6;
        }

        /* ==== ANIMATIONS ==== */
        .quiz-history-section .animate-pulse {
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        .quiz-history-section .animate-fade {
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 768px) {
            .quiz-history-section .d-none.d-md-block {
                display: none !important;
            }

            .quiz-history-section .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .quiz-history-section .table-head tr th,
            .quiz-history-section .quiz-row td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .quiz-history-section .display-5 {
                font-size: 2rem;
            }

            .quiz-history-section .stat-card h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .quiz-history-section .container {
                padding: 0 0.5rem;
            }

            .quiz-history-section .quiz-row td {
                padding: 0.5rem 0.25rem;
            }

            .quiz-history-section .badge {
                padding: 0.4rem 0.8rem;
                font-size: 0.7rem;
            }
        }
    </style>
@endsection

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
                                                 style="width: {{ $result->percentage }}%;"
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
