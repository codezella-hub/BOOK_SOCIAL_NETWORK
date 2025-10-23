<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookTransactionHistory;
use App\Models\FeedBackBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedbackBookController extends Controller
{
    /**
     * Afficher le formulaire de feedback pour un livre emprunté
     */
    public function create(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien l'emprunteur
        if ($transaction->borrower_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la transaction est terminée
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Vous ne pouvez donner un feedback que pour les transactions terminées.');
        }

        // Vérifier si l'utilisateur a déjà donné un feedback pour ce livre
        if ($transaction->book->feedbacks()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Vous avez déjà donné un feedback pour ce livre.');
        }

        return view('user.books.feedback-form', compact('transaction'));
    }

    /**
     * Enregistrer le feedback pour un livre
     */
    public function store(Request $request, BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien l'emprunteur
        if ($transaction->borrower_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la transaction est terminée
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Vous ne pouvez donner un feedback que pour les transactions terminées.');
        }

        // Vérifier si l'utilisateur a déjà donné un feedback pour ce livre
        if ($transaction->book->feedbacks()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Vous avez déjà donné un feedback pour ce livre.');
        }

        // Validation des données
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Analyse sentimentale
            $sentimentAnalysisService = new \App\Services\SentimentAnalysisService();
            $sentiment = $sentimentAnalysisService->analyzeComment($validated['comment']);

            // Créer le feedback
            FeedBackBook::create([
                'book_id' => $transaction->book_id,
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'sentiment' => $sentiment
            ]);

            DB::commit();

            return redirect()->route('user.books.borrowing-history')
                ->with('success', 'Votre feedback a été enregistré avec succès ! Merci pour votre retour.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement du feedback.');
        }
    }

    /**
     * Afficher tous les feedbacks d'un livre
     */
    public function index(Book $book)
    {
        $feedbacks = $book->feedbacks()
            ->with('user')
            ->latest()
            ->paginate(10);

        $ratingStats = $book->rating_stats;

        return view('user.books.feedbacks', compact('book', 'feedbacks', 'ratingStats'));
    }

    /**
     * Afficher le formulaire d'édition d'un feedback
     */
    public function edit(FeedBackBook $feedback)
    {
        // Vérifier que l'utilisateur est bien l'auteur du feedback
        if ($feedback->user_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le feedback a moins de 24h (limite de modification)
        if ($feedback->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Vous ne pouvez modifier votre feedback que dans les 24 heures suivant sa publication.');
        }

        return view('user.books.feedback-edit', compact('feedback'));
    }

    /**
     * Mettre à jour un feedback
     */
    public function update(Request $request, FeedBackBook $feedback)
    {
        // Vérifier que l'utilisateur est bien l'auteur du feedback
        if ($feedback->user_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le feedback a moins de 24h (limite de modification)
        if ($feedback->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Vous ne pouvez modifier votre feedback que dans les 24 heures suivant sa publication.');
        }

        // Validation des données
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $feedback->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'sentiment' => null // Réinitialiser le sentiment pour réanalyse
            ]);

            DB::commit();

            return redirect()->route('books.feedbacks', $feedback->book)
                ->with('success', 'Votre feedback a été modifié avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la modification du feedback.');
        }
    }

    /**
     * Supprimer un feedback
     */
    public function destroy(FeedBackBook $feedback)
    {
        // Vérifier que l'utilisateur est bien l'auteur du feedback
        if ($feedback->user_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le feedback a moins de 1h (limite de suppression)
        if ($feedback->created_at->diffInHours(now()) > 1) {
            return back()->with('error', 'Vous ne pouvez supprimer votre feedback que dans l\'heure suivant sa publication.');
        }

        try {
            DB::beginTransaction();

            $feedback->delete();

            DB::commit();

            return back()->with('success', 'Votre feedback a été supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression du feedback.');
        }
    }

    /**
     * API pour récupérer les feedbacks d'un livre (pour AJAX)
     */
    public function getBookFeedbacks(Book $book)
    {
        $feedbacks = $book->feedbacks()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($feedback) {
                return [
                    'id' => $feedback->id,
                    'user_name' => $feedback->user->name,
                    'rating' => $feedback->rating,
                    'rating_stars' => $feedback->rating_stars,
                    'comment' => $feedback->comment,
                    'created_at' => $feedback->created_at->diffForHumans(),
                    'sentiment_icon' => $feedback->sentiment_icon,
                    'sentiment_color' => $feedback->sentiment_color,
                ];
            });

        return response()->json([
            'feedbacks' => $feedbacks,
            'average_rating' => $book->averageRating(),
            'total_reviews' => $book->totalReviews(),
        ]);
    }

    /**
     * Vérifier si l'utilisateur peut donner un feedback pour une transaction
     */
    public function canGiveFeedback(BookTransactionHistory $transaction)
    {
        $canGiveFeedback = $transaction->status === 'completed' &&
            $transaction->borrower_id === auth()->id() &&
            !$transaction->book->feedbacks()->where('user_id', auth()->id())->exists();

        return response()->json(['can_give_feedback' => $canGiveFeedback]);
    }
}
