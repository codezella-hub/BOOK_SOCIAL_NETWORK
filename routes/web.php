<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookUserController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Admin donation routes
    Route::get('/donations', [DonationController::class, 'adminIndex'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'adminShow'])->name('donations.show');
    Route::patch('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::patch('/donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');


    Route::resource('categories', AdminCategoryController::class);
    // Admin books routes
    Route::resource('books', AdminBookController::class);
    Route::patch('/books/{book}/toggle-archive', [AdminBookController::class, 'toggleArchive'])->name('books.toggle-archive');
    Route::patch('/books/{book}/toggle-shareable', [AdminBookController::class, 'toggleShareable'])->name('books.toggle-shareable');
});

Route::prefix('/')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
});
// Public books routes
Route::get('/books', [BookUserController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookUserController::class, 'show'])->name('books.show');
Route::get('/books1/{book}', [BookUserController::class, 'show1'])->name('books.showDetailsPublic');
// Authenticated user book routes
Route::middleware('auth')->group(function () {
    Route::get('/my-books', [BookUserController::class, 'myBooks'])->name('user.books.my-books');
    Route::get('/my-books/create', [BookUserController::class, 'create'])->name('user.books.create');
    Route::post('/my-books', [BookUserController::class, 'store'])->name('user.books.store');
    Route::get('/my-books/{book}/edit', [BookUserController::class, 'edit'])->name('user.books.edit');
    Route::put('/my-books/{book}', [BookUserController::class, 'update'])->name('user.books.update');
    Route::delete('/my-books/{book}', [BookUserController::class, 'destroy'])->name('user.books.destroy');
    Route::patch('/my-books/{book}/toggle-shareable', [BookUserController::class, 'toggleShareable'])->name('user.books.toggle-shareable');
    Route::patch('/my-books/{book}/toggle-archive', [BookUserController::class, 'toggleArchive'])->name('user.books.toggle-archive');
});
// User donation routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::resource('donations', DonationController::class)->names([
        'index' => 'user.donations.index',
        'create' => 'user.donations.create',
        'store' => 'user.donations.store',
        'show' => 'user.donations.show',
        'edit' => 'user.donations.edit',
        'update' => 'user.donations.update',
        'destroy' => 'user.donations.destroy'
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
