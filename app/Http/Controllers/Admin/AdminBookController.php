<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'user']);

        // Recherche par titre, auteur ou ISBN
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filtrage par statut archived
        if ($request->has('archived') && $request->archived != '') {
            $query->where('archived', $request->archived);
        }

        // Filtrage par statut shareable
        if ($request->has('shareable') && $request->shareable != '') {
            $query->where('shareable', $request->shareable);
        }

        // Filtrage par propriétaire
        if ($request->has('user') && $request->user != '') {
            $query->where('user_id', $request->user);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
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
            default:
                $query->latest();
        }

        $books = $query->paginate(4); // 4 livres par page
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories', 'users'));
    }

    // Les autres méthodes restent inchangées...
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.books.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'synopsis' => 'nullable|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'archived' => 'boolean',
            'shareable' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($request->hasFile('book_cover')) {
            $validated['book_cover'] = $request->file('book_cover')->store('book-covers', 'public');
        }

        $validated['archived'] = $request->has('archived');
        $validated['shareable'] = $request->has('shareable');

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'user']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories', 'users'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'synopsis' => 'nullable|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'archived' => 'boolean',
            'shareable' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($request->hasFile('book_cover')) {
            if ($book->book_cover) {
                Storage::disk('public')->delete($book->book_cover);
            }
            $validated['book_cover'] = $request->file('book_cover')->store('book-covers', 'public');
        }

        $validated['archived'] = $request->has('archived');
        $validated['shareable'] = $request->has('shareable');

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->book_cover) {
            Storage::disk('public')->delete($book->book_cover);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }

    public function toggleArchive(Book $book)
    {
        $book->update(['archived' => !$book->archived]);

        $status = $book->archived ? 'archived' : 'unarchived';

        return redirect()->route('admin.books.index')
            ->with('success', "Book {$status} successfully.");
    }

    public function toggleShareable(Book $book)
    {
        $book->update(['shareable' => !$book->shareable]);

        $status = $book->shareable ? 'made shareable' : 'made private';

        return redirect()->route('admin.books.index')
            ->with('success', "Book {$status} successfully.");
    }
}
