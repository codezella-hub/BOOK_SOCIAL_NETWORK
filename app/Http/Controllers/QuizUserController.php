<?php

namespace App\Http\Controllers;

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

        // Récupérer les résultats de l'utilisateur connecté
        if (Auth::check()) {
            $userResults = Resultats::where('id_user', Auth::id())
                                   ->get()
                                   ->keyBy('id_quiz');
        } else {
            $userResults = collect();
        }

        // CORRECTION : Changer le chemin de la vue
        return view('user.GestionQuiz.index', compact('quizzes', 'userResults'));
    }

    /**
     * Afficher les détails d'un quiz avant de commencer
     */
    public function show(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('user.quiz.index')
                           ->with('error', 'Ce quiz n\'est pas disponible.');
        }

        // Vérifier le nombre de tentatives si l'utilisateur est connecté
        $attemptsCount = 0;
        $previousResults = collect();

        if (Auth::check()) {
            $previousResults = Resultats::where('id_user', Auth::id())
                                       ->where('id_quiz', $quiz->id_quiz)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
            $attemptsCount = $previousResults->count();
        }

        // CORRECTION : Changer le chemin de la vue
        return view('user.GestionQuiz.show', compact('quiz', 'attemptsCount', 'previousResults'));
    }

    /**
     * Commencer un quiz
     */
    public function start(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('user.quiz.index')
                           ->with('error', 'Ce quiz n\'est pas disponible.');
        }

        // Vérifier le nombre de tentatives si l'utilisateur est connecté
        if (Auth::check()) {
            $attemptsCount = Resultats::where('id_user', Auth::id())
                                     ->where('id_quiz', $quiz->id_quiz)
                                     ->count();

            if ($attemptsCount >= $quiz->max_attempts) {
                return redirect()->route('user.quiz.show', $quiz)
                             ->with('error', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz.');
            }
        }

        // Charger les questions
        $questions = $quiz->questions()
                         ->orderBy('order_position')
                         ->take($quiz->nb_questions)
                         ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('user.quiz.show', $quiz)
                           ->with('error', 'Ce quiz n\'a pas encore de questions.');
        }

        // Stocker l'heure de début en session
        session(['quiz_' . $quiz->id_quiz . '_start' => Carbon::now()]);

        // CORRECTION : Changer le chemin de la vue
        return view('user.GestionQuiz.play', compact('quiz', 'questions'));
    }

    /**
     * Soumettre les réponses du quiz
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D'
        ]);

        // Calculer le score
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
        $passed = $percentage >= 70; // Seuil de réussite à 70%

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

            // Nettoyer la session
            session()->forget('quiz_' . $quiz->id_quiz . '_start');
        }

        // Préparer les détails des réponses pour l'affichage
        $reviewData = [];
        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $reviewData[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'is_correct' => $userAnswer === $question->correct_answer
            ];
        }

        // CORRECTION : Changer le chemin de la vue
        return view('user.GestionQuiz.result', compact(
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
    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('error', 'Vous devez être connecté pour voir votre historique.');
        }

        $results = Resultats::where('id_user', Auth::id())
                           ->with('quiz')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        // CORRECTION : Changer le chemin de la vue
        return view('user.GestionQuiz.history', compact('results'));
    }
}
