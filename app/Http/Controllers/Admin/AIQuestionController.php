<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Services\BookContextExtractor;
use App\Services\GroqQuestionGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AIQuestionController extends Controller
{
    private GroqQuestionGenerator $ai;
    private BookContextExtractor $bookCtx;

    public function __construct(GroqQuestionGenerator $ai, BookContextExtractor $bookCtx)
    {
        $this->ai = $ai;
        $this->bookCtx = $bookCtx;
    }

    /**
     * 🧩 Afficher le formulaire de génération IA
     */
    public function showForm(Quiz $quiz)
    {
        $quiz->load('book');

        $defaults = ['book_title' => '', 'book_summary' => '', 'book_excerpt' => ''];
        if ($quiz->book) {
            $defaults = $this->bookCtx->extract($quiz->book);
        }

        return view('admin.GestionQuestion.generate', compact('quiz', 'defaults'));
    }

    /**
     * 🧠 Prévisualisation AJAX avant enregistrement
     */
    public function preview(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'book_title'    => ['required', 'string', 'max:255'],
            'book_summary'  => ['nullable', 'string', 'max:2000'],
            'book_excerpt'  => ['nullable', 'string', 'max:3000'],
            'num_questions' => ['required', 'integer', 'min:1', 'max:20'],
            'difficulty'    => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
        ]);

        $questions = $this->ai->generate($data);

        return response()->json([
            'success' => true,
            'questions' => $questions,
        ]);
    }

    /**
     * 🚀 Génération + Sauvegarde des questions dans la base
     */
    public function generate(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'book_title'          => ['required', 'string', 'max:255'],
            'book_summary'        => ['nullable', 'string', 'max:2000'],
            'book_excerpt'        => ['nullable', 'string', 'max:3000'],
            'num_questions'       => ['required', 'integer', 'min:1', 'max:20'],
            'difficulty'          => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'points_per_question' => ['required', 'numeric', 'min:0.5', 'max:10'],
        ]);

        if ($quiz->book) {
            $auto = $this->bookCtx->extract($quiz->book);
            $data['book_title']   = $data['book_title']   ?: $auto['book_title'];
            $data['book_summary'] = $data['book_summary'] ?: $auto['book_summary'];
            $data['book_excerpt'] = $data['book_excerpt'] ?: $auto['book_excerpt'];
        }

        $generated = $this->ai->generate($data);

        DB::transaction(function () use ($quiz, $generated, $data) {
            $order = (int) (Question::where('id_quiz', $quiz->id_quiz)->max('order_position') ?? 0);
            foreach ($generated as $q) {
                Question::create([
                    'id_quiz'        => $quiz->id_quiz,
                    'question_text'  => $q['question_text'],
                    'option_a'       => $q['option_a'],
                    'option_b'       => $q['option_b'],
                    'option_c'       => $q['option_c'],
                    'option_d'       => $q['option_d'],
                    'correct_answer' => $q['correct_answer'],
                    'explanation'    => $q['explanation'] ?? null,
                    'points'         => $data['points_per_question'],
                    'order_position' => ++$order,
                ]);
            }
        });

        // ✅ Redirection correcte avec le modèle $quiz (pas l'id)
        return redirect()
            ->route('admin.quiz.question.index', ['quiz' => $quiz])
            ->with('success', '✨ ' . count($generated) . ' question(s) générée(s) et sauvegardée(s) avec succès !');
    }
}
