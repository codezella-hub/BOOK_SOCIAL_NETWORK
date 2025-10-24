<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizAdminController extends Controller
{
    /**
     * Afficher la liste des quiz
     */
   public function index(Request $request, $bookId = null)
    {
        $query = Quiz::with(['book', 'questions', 'results']);

        // Recherche textuelle
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrer par livre
        if ($bookId || $request->filled('book_id')) {
            $id = $bookId ?? $request->input('book_id');
            $query->where('id_book', $id);
            $book = Book::find($id);
        } else {
            $book = null;
        }

        // Filtrer par niveau de difficulté
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->input('difficulty'));
        }

        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        // Filtrer par date
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->input('created_from'));
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->input('created_to'));
        }

        // Récupération paginée
        $quizzes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques par quiz
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

        // Statistiques globales
        $globalStats = [
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::where('is_active', true)->count(),
            'avg_success_rate' => round(Quiz::with('results')->get()
                ->filter(fn($q) => $q->results->count() > 0)
                ->avg(fn($q) => $q->results->where('passed', true)->count() / max(1, $q->results->count()) * 100), 1),
        ];

        // Livres disponibles pour le filtre
        $books = Book::orderBy('title')->get();

        return view('admin.GestionQuiz.index', compact('quizzes', 'book', 'books', 'globalStats'));
    }


    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Récupérer tous les livres actifs (non archivés)
        $books = Book::orderBy('title', 'asc')->get();

        // Créer un tableau pour le select avec id => "title - author"
        $bookOptions = $books->mapWithKeys(function ($book) {
            return [$book->id => $book->title . ' - ' . $book->author_name];
        })->toArray();

        $difficultyLevels = [
            'beginner' => 'Débutant',
            'intermediate' => 'Intermédiaire',
            'advanced' => 'Avancé'
        ];

        return view('admin.GestionQuiz.create', compact('bookOptions', 'difficultyLevels'));
    }

    /**
     * Enregistrer un nouveau quiz
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'id_book' => 'required|exists:books,id',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'nb_questions' => 'required|integer|min:1|max:50',
            'max_attempts' => 'required|integer|min:1|max:10',
            'time_limit' => 'required|integer|min:1|max:180',
            'is_active' => 'nullable|boolean'
        ]);

        // Définir is_active par défaut à false si non coché
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Créer le quiz avec l'id_book
        $quiz = Quiz::create($validated);

        // Redirection avec message de succès
        return redirect()->route('admin.quiz.index')
                       ->with('success', 'Quiz "' . $quiz->title . '" créé avec succès et affecté au livre !');
    }

    /**
     * ✅ CORRECTION: Afficher les détails d'un quiz
     * La route envoie 2 paramètres: {book} et {quiz}
     * Laravel injecte automatiquement l'objet Quiz grâce au model binding
     */
    public function show($bookId, Quiz $quiz)
    {
        // Vérification de sécurité: le quiz appartient-il bien au livre ?
        if ($quiz->id_book != $bookId) {
            return redirect()->route('admin.quiz.index')
                           ->with('error', 'Ce quiz n\'appartient pas à ce livre.');
        }

        // Charger les relations nécessaires
        $quiz->load(['book', 'questions', 'results.user']);

        // Calculer les statistiques
        $stats = [
            'total_questions' => $quiz->questions->count(),
            'total_attempts' => $quiz->results->count(),
            'success_rate' => $quiz->results->count() > 0
                ? round($quiz->results->where('passed', true)->count() / $quiz->results->count() * 100, 1)
                : 0,
            'average_score' => round($quiz->results->avg('percentage') ?? 0, 1),
            'unique_participants' => $quiz->results->unique('id_user')->count()
        ];

        return view('admin.GestionQuiz.show', compact('quiz', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Quiz $quiz)
    {
        // Récupérer tous les livres (y compris archivés pour permettre l'édition)
        $books = Book::orderBy('title', 'asc')->get();
        $bookOptions = $books->mapWithKeys(function ($book) {
            return [$book->id => $book->title . ' - ' . $book->author_name];
        })->toArray();

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
            'id_book' => 'required|integer|exists:books,id',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $quiz->update($validated);

        return redirect()->route('admin.quiz.index')
                        ->with('success', 'Quiz "' . $quiz->title . '" mis à jour avec succès !');
    }

    /**
     * Supprimer un quiz
     */
    public function destroy(Quiz $quiz)
    {
        $quizTitle = $quiz->title;

        try {
            $quiz->delete();

            return redirect()->route('admin.quiz.index')
                            ->with('success', 'Quiz "' . $quizTitle . '" supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.quiz.index')
                            ->with('error', 'Erreur lors de la suppression du quiz : ' . $e->getMessage());
        }
    }
}
