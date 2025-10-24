<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionAdminController extends Controller
{
    /**
     * 🧩 Afficher toutes les questions d’un quiz
     */
    public function index(Quiz $quiz)
    {
        // On récupère les questions liées à ce quiz
        $questions = Question::where('id_quiz', $quiz->id_quiz)
                             ->orderBy('order_position')
                             ->get();

        // Envoi vers la vue avec les données
        return view('admin.GestionQuestion.index', compact('quiz', 'questions'));
    }

    /**
     * 🧠 Formulaire de création de question
     */
    public function create(Quiz $quiz)
    {
        // Position suivante (utile pour afficher dans le formulaire)
        $nextPosition = Question::where('id_quiz', $quiz->id_quiz)->max('order_position') + 1;

        return view('admin.GestionQuestion.create', compact('quiz', 'nextPosition'));
    }

    /**
     * 💾 Enregistrer une nouvelle question
     */
    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|numeric|min:0.5|max:10',
            'order_position' => 'nullable|integer|min:1',
        ]);

        // Définir les champs supplémentaires
        $validated['id_quiz'] = $quiz->id_quiz;
        $validated['order_position'] = $validated['order_position']
            ?? (Question::where('id_quiz', $quiz->id_quiz)->max('order_position') + 1);

        // Créer la question
        Question::create($validated);

        // Redirection après succès
        return redirect()
            ->route('admin.quiz.question.index', $quiz)
            ->with('success', '✅ Nouvelle question ajoutée avec succès !');
    }

    /**
     * ✏️ Formulaire d’édition d’une question existante
     */
    public function edit(Quiz $quiz, Question $question)
    {
        // Vérifier que la question appartient bien à ce quiz
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404, 'Cette question ne correspond pas à ce quiz.');
        }

        return view('admin.GestionQuestion.edit', compact('quiz', 'question'));
    }

    /**
     * 🔄 Mettre à jour une question existante
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        // Vérification d’appartenance
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404, 'Cette question ne correspond pas à ce quiz.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|numeric|min:0.5|max:10',
            'order_position' => 'required|integer|min:1',
        ]);

        $question->update($validated);

        return redirect()
            ->route('admin.quiz.question.index', $quiz)
            ->with('success', '✏️ Question mise à jour avec succès !');
    }
public function show(Quiz $quiz, Question $question)
{
    if ($question->id_quiz !== $quiz->id_quiz) {
        abort(404, 'Cette question ne correspond pas à ce quiz.');
    }

    return view('admin.GestionQuestion.show', compact('quiz', 'question'));
}

    /**
     * 🗑️ Supprimer une question
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        if ($question->id_quiz !== $quiz->id_quiz) {
            abort(404, 'Cette question ne correspond pas à ce quiz.');
        }

        $question->delete();

        return redirect()
            ->route('admin.quiz.question.index', $quiz)
            ->with('success', '🗑️ Question supprimée avec succès.');
    }
}
