<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Resultats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizUserController extends Controller
{
    /**
     * Afficher la liste des quiz disponibles
     */
    public function index()
    {
        $quizzes = Quiz::where('is_active', true)
            ->with('questions')
            ->withCount('questions')
            ->get();

        $userResults = Auth::check()
            ? Resultats::where('id_user', Auth::id())->get()->keyBy('id_quiz')
            : collect();

        return view('user.GestionQuiz.index', compact('quizzes', 'userResults'));
    }

    /**
     * Afficher les détails d'un quiz avant de commencer
     */
    public function show(Book $book, Quiz $quiz)
    {
        if ($quiz->id_book !== $book->id) {
            return redirect()->route('user.quiz.index')
                ->with('error', 'Ce quiz ne correspond pas au livre sélectionné.');
        }

        if (!$quiz->is_active) {
            return redirect()->route('user.quiz.index')
                ->with('error', 'Ce quiz n\'est pas disponible.');
        }

        $attemptsCount = 0;
        $previousResults = collect();

        if (Auth::check()) {
            $previousResults = Resultats::where('id_user', Auth::id())
                ->where('id_quiz', $quiz->id_quiz)
                ->orderBy('created_at', 'desc')
                ->get();
            $attemptsCount = $previousResults->count();
        }

        return view('user.GestionQuiz.show', compact('book', 'quiz', 'attemptsCount', 'previousResults'));
    }

    /**
     * Commencer un quiz
     */
    public function start(Book $book, Quiz $quiz)
    {
        if ($quiz->id_book !== $book->id) {
            return redirect()->route('user.quiz.index')
                ->with('error', 'Ce quiz ne correspond pas au livre sélectionné.');
        }

        if (!$quiz->is_active) {
            return redirect()->route('user.quiz.index')
                ->with('error', 'Ce quiz n\'est pas disponible.');
        }

        if (Auth::check()) {
            $attemptsCount = Resultats::where('id_user', Auth::id())
                ->where('id_quiz', $quiz->id_quiz)
                ->count();

            if ($attemptsCount >= $quiz->max_attempts) {
                return redirect()->route('user.quiz.show', [$book, $quiz])
                    ->with('error', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz.');
            }
        }

        $questions = $quiz->questions()
            ->orderBy('order_position')
            ->take($quiz->nb_questions)
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('user.quiz.show', [$book, $quiz])
                ->with('error', 'Ce quiz n\'a pas encore de questions.');
        }

        session(['quiz_' . $quiz->id_quiz . '_start' => Carbon::now()]);

        return view('user.GestionQuiz.play', compact('book', 'quiz', 'questions'));
    }

    /**
     * Soumettre les réponses du quiz
     */
    public function submit(Request $request, Book $book, Quiz $quiz)
    {
        if ($quiz->id_book !== $book->id) {
            return redirect()->route('user.quiz.index')
                ->with('error', 'Ce quiz ne correspond pas au livre sélectionné.');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D'
        ]);

        $questions = $quiz->questions()
            ->orderBy('order_position')
            ->take($quiz->nb_questions)
            ->get();

        $totalPoints = 0;
        $earnedPoints = 0;
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $userAnswer = $request->answers[$question->id] ?? null;

            if ($userAnswer === $question->correct_answer) {
                $earnedPoints += $question->points;
                $correctAnswers++;
            }
        }

        $percentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $percentage >= 70;

        // Créer le résultat si l'utilisateur est connecté
        $result = null;

        if (Auth::check()) {
            $attemptNumber = Resultats::where('id_user', Auth::id())
                ->where('id_quiz', $quiz->id_quiz)
                ->count() + 1;

            $startTime = session('quiz_' . $quiz->id_quiz . '_start');

            $result = Resultats::create([
                'id_quiz' => $quiz->id_quiz,
                'id_user' => Auth::id(),
                'attempt_number' => $attemptNumber,
                'score' => $earnedPoints,
                'percentage' => $percentage,
                'total_questions' => $questions->count(),
                'correct_answers' => $correctAnswers,
                'passed' => $passed,
                'started_at' => $startTime ?? Carbon::now(),
                'completed_at' => Carbon::now()
            ]);

            session()->forget('quiz_' . $quiz->id_quiz . '_start');

            /**
             * ✅ Rafraîchir les composants Livewire (compatible Livewire v3 / Volt)
             */
            if (class_exists(\Livewire\Features\SupportEvents\Event::class)) {
                event(new \Livewire\Features\SupportEvents\Event('quizUpdated', []));
            }
        }

        // Préparer les détails pour l'affichage
        $reviewData = [];
        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $reviewData[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'is_correct' => $userAnswer === $question->correct_answer
            ];
        }

        return view('user.GestionQuiz.result', compact(
            'book',
            'quiz',
            'result',
            'earnedPoints',
            'totalPoints',
            'percentage',
            'passed',
            'correctAnswers',
            'reviewData'
        ));
    }

    /**
     * Voir l'historique des résultats d'un utilisateur
     */
    public function history(Request $request)
    {
        $query = Resultats::where('id_user', Auth::id())
            ->with('quiz')
            ->orderBy('completed_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('quiz', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $results = $query->paginate(10)->appends(['search' => $request->search]);
        return view('user.GestionQuiz.history', compact('results'));
    }

    /**
     * Lister les quiz d'un livre
     */
    public function byBook($bookId)
    {
        $book = Book::findOrFail($bookId);

        $quizzes = Quiz::where('id_book', $bookId)
            ->where('is_active', true)
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        $userResults = Auth::check()
            ? Resultats::where('id_user', Auth::id())->get()->keyBy('id_quiz')
            : collect();

        return view('user.GestionQuiz.byBook', compact('book', 'quizzes', 'userResults'));
    }
}
