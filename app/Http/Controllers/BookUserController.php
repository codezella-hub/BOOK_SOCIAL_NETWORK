<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookTransactionHistory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookUserController extends Controller
{
    public function index(Request $request)
    {
        // Query for SHAREABLE books only
        $query = Book::with(['category', 'user'])
            ->where('shareable', true)
            ->where('archived', false);

        // Search in SHAREABLE books
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Remove shareable filter since we're only showing shareable books
        // if ($request->has('shareable') && $request->shareable != '') {
        //     $query->where('shareable', $request->shareable);
        // }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'author_asc':
                $query->orderBy('author_name', 'asc');
                break;
            case 'author_desc':
                $query->orderBy('author_name', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(12);
        $categories = Category::orderBy('name')->get();

        // Stats for SHAREABLE books only
        $totalBooks = Book::where('shareable', true)->where('archived', false)->count();
        $totalShareableBooks = $totalBooks; // Since we only show shareable books
        $totalPrivateBooks = Book::where('shareable', false)->count();
        $totalArchivedBooks = Book::where('archived', true)->count();
        $totalUsers = User::has('books')->count();
        $totalCategories = Category::has('books')->count();

        return view('user.books.index', compact(
            'books',
            'categories',
            'totalBooks',
            'totalShareableBooks',
            'totalPrivateBooks',
            'totalArchivedBooks',
            'totalUsers',
            'totalCategories'
        ));
    }

    /**
     * Display user's personal books (only for authenticated users)
     */
    public function myBooks(Request $request)
    {
        $query = Book::with(['category'])
            ->where('user_id', auth()->id());

        // Search in personal books
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(8);

        return view('user.books.my-books', compact('books'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('user.books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'synopsis' => 'nullable|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'shareable' => 'boolean'
        ]);

        // Handle book cover upload
        if ($request->hasFile('book_cover')) {
            $validated['book_cover'] = $request->file('book_cover')->store('book-covers', 'public');
        }

        // Set default values
        $validated['user_id'] = auth()->id();
        $validated['shareable'] = $request->has('shareable');
        $validated['archived'] = false;

        Book::create($validated);

        return redirect()->route('user.books.my-books')
            ->with('success', 'Votre livre a été ajouté avec succès !');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        // Check if book is shareable or belongs to current user
        if (!$book->shareable && $book->user_id != auth()->id()) {
            abort(403, 'Ce livre est privé.');
        }

        $book->load(['category', 'user']);
        $relatedBooks = Book::with(['category', 'user'])
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('shareable', true)
            ->where('archived', false)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('user.books.show', compact('book', 'relatedBooks'));
    }
    /**
     * Display the specified book
     */
    public function show1(Book $book)
    {
        // Check if book is shareable or belongs to current user
        if (!$book->shareable && $book->user_id != auth()->id()) {
            abort(403, 'Ce livre est privé.');
        }

        $book->load(['category', 'user', 'feedbacks.user']);

        // Vérifier le statut d'emprunt actuel
        $currentBorrowStatus = null;
        if (auth()->check()) {
            $currentBorrow = BookTransactionHistory::where('book_id', $book->id)
                ->where('borrower_id', auth()->id())
                ->whereIn('status', ['pending', 'approved', 'borrowed'])
                ->first();

            $currentBorrowStatus = $currentBorrow ? $currentBorrow->status : null;
        }

        $relatedBooks = Book::with(['category', 'user'])
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('shareable', true)
            ->where('archived', false)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('user.books.showDetailsPublic', compact('book', 'relatedBooks', 'currentBorrowStatus'));
    }

    /**
     * Show the form for editing the book
     */
    public function edit(Book $book)
    {
        // Check if user owns the book
        if ($book->user_id != auth()->id()) {
            abort(403, 'Vous ne pouvez pas modifier ce livre.');
        }

        $categories = Category::orderBy('name')->get();
        return view('user.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, Book $book)
    {
        // Check if user owns the book
        if ($book->user_id != auth()->id()) {
            abort(403, 'Vous ne pouvez pas modifier ce livre.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'synopsis' => 'nullable|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'shareable' => 'boolean'
        ]);

        // Handle book cover upload
        if ($request->hasFile('book_cover')) {
            // Delete old cover if exists
            if ($book->book_cover) {
                Storage::disk('public')->delete($book->book_cover);
            }
            $validated['book_cover'] = $request->file('book_cover')->store('book-covers', 'public');
        }

        $validated['shareable'] = $request->has('shareable');

        $book->update($validated);

        return redirect()->route('user.books.my-books')
            ->with('success', 'Livre mis à jour avec succès !');
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        // Check if user owns the book
        if ($book->user_id != auth()->id()) {
            abort(403, 'Vous ne pouvez pas supprimer ce livre.');
        }

        // Delete book cover if exists
        if ($book->book_cover) {
            Storage::disk('public')->delete($book->book_cover);
        }

        $book->delete();

        return redirect()->route('user.books.my-books')
            ->with('success', 'Livre supprimé avec succès !');
    }

    public function toggleShareable(Book $book)
    {
        if ($book->user_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Si le livre est archivé, on ne peut pas le rendre partageable
        if ($book->archived && !$book->shareable) {
            return back()->with('error', 'Impossible de partager un livre archivé. Veuillez d\'abord le désarchiver.');
        }

        $book->update(['shareable' => !$book->shareable]);

        $status = $book->shareable ? 'partageable' : 'privé';

        return back()->with('success', "Livre marqué comme {$status} !");
    }

    /**
     * Toggle archive status
     */
    public function toggleArchive(Book $book)
    {
        if ($book->user_id != auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Si on archive un livre partageable, on le rend automatiquement privé
        if (!$book->archived && $book->shareable) {
            $book->update([
                'archived' => true,
                'shareable' => false
            ]);
            return back()->with('success', "Livre archivé et rendu privé avec succès !");
        }

        // Si on désarchive un livre
        $book->update(['archived' => !$book->archived]);

        $status = $book->archived ? 'archivé' : 'désarchivé';

        return back()->with('success', "Livre {$status} avec succès !");
    }
}
