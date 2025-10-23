<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class UserQuizProgress extends Component
{
    public $quizId;
    public $userId;
    public $totalQuestions = 0;
    public $correctAnswers = 0;
    public $percentage = 0;
    public $passed = false;
    public $answers = [];

    // 👂 Livewire 3 écoute directement les paramètres
    protected $listeners = ['answerSelected' => 'updateProgress'];

    public function mount($quizId, $totalQuestions)
    {
        $this->quizId = $quizId;
        $this->userId = Auth::id();
        $this->totalQuestions = $totalQuestions;
    }

    // ✅ Correction ici : deux arguments séparés
    public function updateProgress($questionId, $selectedAnswer)
    {
        // Enregistre la réponse utilisateur
        $this->answers[$questionId] = $selectedAnswer;

        // Récupère toutes les questions de ce quiz
        $questions = Question::where('id_quiz', $this->quizId)->get();

        $this->correctAnswers = 0;

        foreach ($questions as $q) {
            if (isset($this->answers[$q->id]) && $this->answers[$q->id] === $q->correct_answer) {
                $this->correctAnswers++;
            }
        }

        // Calcule le pourcentage
        $this->percentage = $this->totalQuestions > 0
            ? round(($this->correctAnswers / $this->totalQuestions) * 100, 1)
            : 0;

        $this->passed = $this->percentage >= 70;
    }

    public function render()
    {
        return view('livewire.user-quiz-progress');
    }
}
