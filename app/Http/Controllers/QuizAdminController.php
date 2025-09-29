<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizAdminController extends Controller
{
    /**
     * Afficher la liste des quiz
     */
    public function index()
    {
        $quizzes = Quiz::with(['questions', 'results'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        // Ajouter les statistiques
        $quizzes->getCollection()->transform(function ($quiz) {
            $quiz->stats = [
                'total_questions' => $quiz->questions->count(),
                'total_attempts' => $quiz->results->count(),
                'success_rate' => $quiz->results->count() > 0
                    ? round($quiz->results->where('passed', true)->count() / $quiz->results->count() * 100, 1)
                    : 0
            ];
            return $quiz;
        });

        return view('admin.GestionQuiz.index', compact('quizzes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $bookOptions = [
            1 => 'Harry Potter - Tome 1',
            2 => 'Harry Potter - Tome 2',
            3 => 'Le Seigneur des Anneaux',
            4 => 'Game of Thrones',
            5 => 'Les Misérables',
            6 => '1984',
            7 => 'Le Petit Prince'
        ];

        $difficultyLevels = [
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé'
        ];

        return view('admin.GestionQuiz.create', compact('bookOptions', 'difficultyLevels'));
    }

    /**
     * Sauvegarder un nouveau quiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'nb_questions' => 'required|integer|min:1|max:50',
            'max_attempts' => 'required|integer|min:1|max:10',
            'time_limit' => 'required|integer|min:1|max:180',
            'id_book' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Quiz::create($validated);

 return redirect()->route('admin.quiz.index')
                ->with('success', 'Quiz créé avec succès !');
}

    /**
     * Afficher un quiz spécifique
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'results.user']);

        $stats = [
            'total_questions' => $quiz->questions->count(),
            'total_attempts' => $quiz->results->count(),
            'success_rate' => $quiz->results->count() > 0
                ? round($quiz->results->where('passed', true)->count() / $quiz->results->count() * 100, 1)
                : 0,
            'average_score' => $quiz->results->avg('percentage') ?? 0,
            'unique_participants' => $quiz->results->unique('id_user')->count()
        ];

        return view('admin.GestionQuiz.show', compact('quiz', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Quiz $quiz)
    {
        $bookOptions = [
            1 => 'Harry Potter - Tome 1',
            2 => 'Harry Potter - Tome 2',
            3 => 'Le Seigneur des Anneaux',
            4 => 'Game of Thrones',
            5 => 'Les Misérables',
            6 => '1984',
            7 => 'Le Petit Prince'
        ];

        $difficultyLevels = [
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé'
        ];

        return view('admin.GestionQuiz.edit', compact('quiz', 'bookOptions', 'difficultyLevels'));
    }

    /**
     * Mettre à jour un quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'nb_questions' => 'required|integer|min:1|max:50',
            'max_attempts' => 'required|integer|min:1|max:10',
            'time_limit' => 'required|integer|min:1|max:180',
            'id_book' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $quiz->update($validated);

  return redirect()->route('admin.quiz.index')
                ->with('success', 'Quiz mis à jour avec succès !');

    }

    /**
     * Supprimer un quiz
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
return redirect()->route('admin.quiz.index')
                ->with('success', 'Quiz supprimé avec succès !');
    }
}
