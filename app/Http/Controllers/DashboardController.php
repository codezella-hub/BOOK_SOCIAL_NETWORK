<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookTransactionHistory;
use App\Models\FeedBackBook;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques principales
        $stats = $this->getMainStats();

        // Données pour les 4 graphiques
        $chartData = $this->getChartData();

        return view('admin.dashboard.dashboard', compact('stats', 'chartData'));
    }

    private function getMainStats()
    {
        return [
            // Livres
            'total_books' => Book::count(),
            'shareable_books' => Book::where('shareable', true)->count(),
            'archived_books' => Book::where('archived', true)->count(),

            // Utilisateurs
            'total_users' => User::count(),
            'active_users' => User::whereHas('borrowedBooks')->orWhereHas('lentBooks')->count(),

            // Transactions
            'total_transactions' => BookTransactionHistory::count(),
            'active_transactions' => BookTransactionHistory::where('status', 'borrowed')->count(),
            'overdue_transactions' => BookTransactionHistory::overdue()->count(),

            // Feedbacks
            'total_feedbacks' => FeedBackBook::count(),
            'avg_rating' => round(FeedBackBook::avg('rating') ?? 0, 1),

            // Quiz
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::active()->count(),
        ];
    }

    private function getChartData()
    {
        // Graphique 1: Livres par catégorie (Top 6)
        $booksByCategory = Category::withCount(['books as book_count'])
            ->orderBy('book_count', 'desc')
            ->limit(6)
            ->get();

        // Graphique 2: Distribution des notes des livres
        $ratingDistribution = FeedBackBook::select(
            'rating',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        // Graphique 3: Top 10 utilisateurs les plus actifs (CORRIGÉ)
        $topUsers = User::select([
            'users.id',
            'users.name',
            'users.email',
            DB::raw('(SELECT COUNT(*) FROM book_transaction_histories WHERE borrower_id = users.id) as total_borrowed'),
            DB::raw('(SELECT COUNT(*) FROM book_transaction_histories WHERE lender_id = users.id) as total_lent')
        ])
            ->havingRaw('total_borrowed > 0 OR total_lent > 0')
            ->orderByRaw('(total_borrowed + total_lent) DESC')
            ->limit(10)
            ->get();

        // Graphique 4: Quiz par niveau de difficulté
        $quizzesByDifficulty = Quiz::select(
            'difficulty_level',
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(nb_questions) as avg_questions'),
            DB::raw('AVG(time_limit) as avg_time')
        )
            ->groupBy('difficulty_level')
            ->get();

        return [
            'booksByCategory' => $booksByCategory,
            'ratingDistribution' => $ratingDistribution,
            'topUsers' => $topUsers,
            'quizzesByDifficulty' => $quizzesByDifficulty,
        ];
    }
}
