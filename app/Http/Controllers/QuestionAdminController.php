<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionAdminController extends Controller
{
    /**
     * Afficher toutes les questions d'un quiz
     */
    public function index(Quiz $quiz)
    {
        // Charger les questions triées par position
        $questions = $quiz->questions()
                         ->orderBy('order_position')
                         ->get();

        return view('admin.GestionQuestion.index', compact('quiz', 'questions'));
    }

    /**
     * Formulaire de création d'une question
     */
    public function create(Quiz $quiz)
    {
        // Déterminer la prochaine position
        $nextPosition = $quiz->questions()->max('order_position') + 1;

        return view('admin.GestionQuestion.create', compact('quiz', 'nextPosition'));
    }

    /**
     * Sauvegarder une nouvelle question
     */
    public function store(Request $request, Quiz $quiz)
    {
        // Validation des données
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|numeric|min:0|max:100',
            'order_position' => 'required|integer|min:1'
        ]);

        // Ajouter l'ID du quiz
        $validated['id_quiz'] = $quiz->id_quiz;

        // Créer la question
        Question::create($validated);

        // Vérifier si on veut ajouter une autre question
        if ($request->input('action') === 'save_and_new') {
            return redirect()
                ->route('admin.question.create', $quiz)
                ->with('success', 'Question créée avec succès ! Vous pouvez ajouter une autre question.');
        }

        return redirect()
            ->route('admin.question.index', $quiz)
            ->with('success', 'Question créée avec succès !');
    }

    /**
     * Afficher une question spécifique
     */
    public function show(Quiz $quiz, Question $question)
    {
        // Vérifier que la question appartient bien au quiz
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404);
        }

        return view('admin.GestionQuestion.show', compact('quiz', 'question'));
    }

    /**
     * Formulaire d'édition d'une question
     */
    public function edit(Quiz $quiz, Question $question)
    {
        // Vérifier que la question appartient bien au quiz
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404);
        }

        return view('admin.GestionQuestion.edit', compact('quiz', 'question'));
    }

    /**
     * Mettre à jour une question
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        // Vérifier que la question appartient bien au quiz
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404);
        }

        // Validation des données
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|numeric|min:0|max:100',
            'order_position' => 'required|integer|min:1'
        ]);

        // Mettre à jour la question
        $question->update($validated);

        return redirect()
            ->route('admin.question.index', $quiz)
            ->with('success', 'Question mise à jour avec succès !');
    }

    /**
     * Supprimer une question
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        // Vérifier que la question appartient bien au quiz
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404);
        }

        $question->delete();

        // Réorganiser les positions des questions restantes
        $quiz->questions()
             ->where('order_position', '>', $question->order_position)
             ->decrement('order_position');

        return redirect()
            ->route('admin.question.index', $quiz)
            ->with('success', 'Question supprimée avec succès !');
    }

    /**
     * Réorganiser l'ordre des questions (AJAX)
     */
    public function reorder(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id'
        ]);

        foreach ($request->questions as $position => $questionId) {
            Question::where('id', $questionId)
                   ->where('id_quiz', $quiz->id_quiz)
                   ->update(['order_position' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
