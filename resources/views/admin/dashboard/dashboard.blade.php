@extends('layouts.admin-layout')

@section('title', 'Dashboard Analytics')
@section('page-title', 'Tableau de Bord Analytics')

@section('styles')
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --purple: #6f42c1;
            --pink: #e83e8c;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-left: 5px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.books { border-left-color: var(--primary); }
        .stat-card.users { border-left-color: var(--success); }
        .stat-card.transactions { border-left-color: var(--warning); }
        .stat-card.feedbacks { border-left-color: var(--info); }
        .stat-card.quizzes { border-left-color: var(--purple); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-card.books .stat-icon { background: linear-gradient(135deg, var(--primary), #0056b3); }
        .stat-card.users .stat-icon { background: linear-gradient(135deg, var(--success), #1e7e34); }
        .stat-card.transactions .stat-icon { background: linear-gradient(135deg, var(--warning), #e0a800); }
        .stat-card.feedbacks .stat-icon { background: linear-gradient(135deg, var(--info), #138496); }
        .stat-card.quizzes .stat-icon { background: linear-gradient(135deg, var(--purple), #59359a); }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            color: #2c3e50;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .chart-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }

        .chart-title i {
            margin-right: 0.75rem;
            color: var(--primary);
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        .user-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f8f9fa;
            transition: background-color 0.2s;
        }

        .user-item:hover {
            background-color: #f8f9fa;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--info));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-weight: bold;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #2c3e50;
        }

        .user-email {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .user-stats {
            text-align: right;
        }

        .user-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .user-label {
            font-size: 0.8rem;
            color: #6c757d;
        }

        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                height: 300px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card books">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_books']) }}</div>
                        <div class="stat-label">Total Livres</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">
                            {{ $stats['shareable_books'] }} partageables • {{ $stats['archived_books'] }} archivés
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card users">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
                        <div class="stat-label">Utilisateurs</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">
                            {{ $stats['active_users'] }} utilisateurs actifs
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card transactions">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_transactions']) }}</div>
                        <div class="stat-label">Transactions</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">
                            {{ $stats['active_transactions'] }} actives • {{ $stats['overdue_transactions'] }} retards
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card feedbacks">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_feedbacks']) }}</div>
                        <div class="stat-label">Avis & Notes</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">
                            Note moyenne: {{ $stats['avg_rating'] }}/5
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card quizzes">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_quizzes']) }}</div>
                        <div class="stat-label">Quiz Créés</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">
                            {{ $stats['active_quizzes'] }} quiz actifs
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Graphique 1: Livres par Catégorie -->
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-tags"></i>
                    Livres par Catégorie
                </div>
                <div class="chart-wrapper">
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>

            <!-- Graphique 2: Distribution des Notes -->
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-star"></i>
                    Distribution des Notes
                </div>
                <div class="chart-wrapper">
                    <canvas id="ratingsChart"></canvas>
                </div>
            </div>

            <!-- Graphique 3: Top 10 Utilisateurs Actifs -->
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-user-check"></i>
                    Top 10 Utilisateurs Actifs
                </div>
                <div class="chart-wrapper">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>

            <!-- Graphique 4: Quiz par Difficulté -->
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Quiz par Niveau de Difficulté
                </div>
                <div class="chart-wrapper">
                    <canvas id="quizzesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Liste des utilisateurs actifs -->
        <div class="chart-container">
            <div class="chart-title">
                <i class="fas fa-trophy"></i>
                Classement des Utilisateurs les Plus Actifs
            </div>
            <ul class="user-list">
                @foreach($chartData['topUsers'] as $index => $user)
                    <li class="user-item">
                        <div class="user-avatar">
                            {{ Str::of($user->name)->explode(' ')->take(2)->map(fn ($word) => Str::substr($word, 0, 1))->implode('') }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                        <div class="user-stats">
                            <div class="user-value">{{ $user->total_borrowed + $user->total_lent }}</div>
                            <div class="user-label">transactions</div>
                            <div style="font-size: 0.75rem; color: #6c757d;">
                                📥 {{ $user->total_borrowed }} emprunts • 📤 {{ $user->total_lent }} prêts
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique 1: Livres par Catégorie (Barres)
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            const categoriesChart = new Chart(categoriesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['booksByCategory']->pluck('name')) !!},
                    datasets: [{
                        label: 'Nombre de Livres',
                        data: {!! json_encode($chartData['booksByCategory']->pluck('book_count')) !!},
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#17a2b8'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Graphique 2: Distribution des Notes (Radar)
            const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');
            const ratingsChart = new Chart(ratingsCtx, {
                type: 'radar',
                data: {
                    labels: ['⭐ 5 étoiles', '⭐⭐ 4 étoiles', '⭐⭐⭐ 3 étoiles', '⭐⭐⭐⭐ 2 étoiles', '⭐⭐⭐⭐⭐ 1 étoile'],
                    datasets: [{
                        label: 'Nombre d\'Avis',
                        data: {!! json_encode($chartData['ratingDistribution']->pluck('count')) !!},
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        borderColor: '#ffc107',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffc107',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#ffc107'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Graphique 3: Top 10 Utilisateurs Actifs (Barres horizontales)
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            const usersChart = new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['topUsers']->pluck('name')->map(function($name) {
            return strlen($name) > 15 ? substr($name, 0, 15) . '...' : $name;
        })) !!},
                    datasets: [
                        {
                            label: 'Emprunts',
                            data: {!! json_encode($chartData['topUsers']->pluck('total_borrowed')) !!},
                            backgroundColor: '#28a745'
                        },
                        {
                            label: 'Prêts',
                            data: {!! json_encode($chartData['topUsers']->pluck('total_lent')) !!},
                            backgroundColor: '#17a2b8'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            beginAtZero: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });

            // Graphique 4: Quiz par Difficulté (Doughnut)
            const quizzesCtx = document.getElementById('quizzesChart').getContext('2d');
            const quizzesChart = new Chart(quizzesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartData['quizzesByDifficulty']->pluck('difficulty_level')) !!},
                    datasets: [{
                        data: {!! json_encode($chartData['quizzesByDifficulty']->pluck('count')) !!},
                        backgroundColor: [
                            '#28a745', // Débutant - Vert
                            '#ffc107', // Intermédiaire - Jaune
                            '#dc3545'  // Avancé - Rouge
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    const data = {!! json_encode($chartData['quizzesByDifficulty']) !!};
                                    const item = data[context.dataIndex];
                                    return `Moyenne: ${Math.round(item.avg_questions)} questions, ${Math.round(item.avg_time)} min`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
