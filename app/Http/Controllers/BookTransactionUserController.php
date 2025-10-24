<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookTransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookTransactionUserController extends Controller
{
    /**
     * Demander à emprunter un livre
     */
    public function borrowRequest(Book $book)
    {
        // Vérifier si le livre peut être emprunté
        if (!$book->canBeBorrowed()) {
            return back()->with('error', 'Ce livre n\'est pas disponible pour l\'emprunt.');
        }

        // Vérifier si l'utilisateur peut emprunter ce livre
        if (!auth()->user()->canBorrowBook($book)) {
            return back()->with('error', 'Vous ne pouvez pas emprunter ce livre.');
        }

        try {
            DB::beginTransaction();

            // Créer la demande d'emprunt
            BookTransactionHistory::create([
                'book_id' => $book->id,
                'borrower_id' => auth()->id(),
                'lender_id' => $book->user_id,
                'borrowed_date' => now(),
                'due_date' => now()->addDays(14), // 2 semaines par défaut
                'status' => 'pending',
                'notes' => 'Demande d\'emprunt en attente'
            ]);

            DB::commit();

            return back()->with('success', 'Votre demande d\'emprunt a été envoyée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la demande d\'emprunt.');
        }
    }

    /**
     * Afficher l'historique des emprunts de l'utilisateur
     */
    public function myBorrowingHistory(Request $request)
    {
        $query = BookTransactionHistory::with(['book', 'lender'])
            ->where('borrower_id', auth()->id());

        // Filtres
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10);

        $stats = [
            'pending' => BookTransactionHistory::where('borrower_id', auth()->id())->where('status', 'pending')->count(),
            'borrowed' => BookTransactionHistory::where('borrower_id', auth()->id())->where('status', 'borrowed')->count(),
            'returned' => BookTransactionHistory::where('borrower_id', auth()->id())->where('status', 'returned')->count(),
            'completed' => BookTransactionHistory::where('borrower_id', auth()->id())->where('status', 'completed')->count(),
        ];

        return view('user.books.borrowing-history', compact('transactions', 'stats'));
    }

    /**
     * Afficher les demandes d'emprunt pour mes livres
     */
    public function myLendingRequests(Request $request)
    {
        $query = BookTransactionHistory::with(['book', 'borrower'])
            ->where('lender_id', auth()->id())
            ->whereIn('status', ['pending', 'approved', 'borrowed', 'returned']);

        // Filtres
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10);

        $stats = [
            'pending' => BookTransactionHistory::where('lender_id', auth()->id())->where('status', 'pending')->count(),
            'approved' => BookTransactionHistory::where('lender_id', auth()->id())->where('status', 'approved')->count(),
            'borrowed' => BookTransactionHistory::where('lender_id', auth()->id())->where('status', 'borrowed')->count(),
            'returned' => BookTransactionHistory::where('lender_id', auth()->id())->where('status', 'returned')->count(),
        ];

        return view('user.books.lending-requests', compact('transactions', 'stats'));
    }

    /**
     * Approuver une demande d'emprunt
     */
    public function approveRequest(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du livre
        if ($transaction->lender_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la demande est en attente
        if ($transaction->status != 'pending') {
            return back()->with('error', 'Cette demande ne peut pas être approuvée.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'approved',
                'notes' => 'Demande approuvée par le propriétaire'
            ]);

            DB::commit();

            return back()->with('success', 'Demande d\'emprunt approuvée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'approbation.');
        }
    }

    /**
     * Rejeter une demande d'emprunt
     */
    public function rejectRequest(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du livre
        if ($transaction->lender_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la demande est en attente
        if ($transaction->status != 'pending') {
            return back()->with('error', 'Cette demande ne peut pas être rejetée.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'rejected',
                'notes' => 'Demande rejetée par le propriétaire'
            ]);

            DB::commit();

            return back()->with('success', 'Demande d\'emprunt rejetée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du rejet.');
        }
    }

    /**
     * Marquer un livre comme emprunté (quand le livre est remis physiquement)
     */
    public function markAsBorrowed(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du livre
        if ($transaction->lender_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la demande est approuvée
        if ($transaction->status != 'approved') {
            return back()->with('error', 'Cette demande ne peut pas être marquée comme empruntée.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'borrowed',
                'borrowed_date' => now(),
                'notes' => 'Livre remis à l\'emprunteur'
            ]);

            DB::commit();

            return back()->with('success', 'Livre marqué comme emprunté avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Marquer un livre comme retourné (par l'emprunteur)
     */
    public function markAsReturned(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien l'emprunteur
        if ($transaction->borrower_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le livre est emprunté
        if ($transaction->status != 'borrowed') {
            return back()->with('error', 'Ce livre n\'est pas actuellement emprunté.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'returned',
                'returned_date' => now(),
                'returned' => true,
                'notes' => 'Livre retourné par l\'emprunteur - En attente de confirmation'
            ]);

            DB::commit();

            return back()->with('success', 'Livre marqué comme retourné ! En attente de confirmation du propriétaire.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Confirmer le retour d'un livre (par le propriétaire)
     */
    public function confirmReturn(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du livre
        if ($transaction->lender_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le livre est marqué comme retourné
        if ($transaction->status != 'returned') {
            return back()->with('error', 'Ce livre n\'a pas été marqué comme retourné.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'completed',
                'returned_approved' => true,
                'notes' => 'Retour confirmé par le propriétaire - Transaction terminée'
            ]);

            DB::commit();

            return back()->with('success', 'Retour du livre confirmé ! Transaction terminée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Annuler une demande d'emprunt (par l'emprunteur)
     */
    public function cancelRequest(BookTransactionHistory $transaction)
    {
        // Vérifier que l'utilisateur est bien l'emprunteur
        if ($transaction->borrower_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que la demande est en attente
        if ($transaction->status != 'pending') {
            return back()->with('error', 'Cette demande ne peut pas être annulée.');
        }

        try {
            DB::beginTransaction();

            $transaction->delete();

            DB::commit();

            return back()->with('success', 'Demande d\'emprunt annulée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation.');
        }
    }
}
