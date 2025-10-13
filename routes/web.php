<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookUserController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizAdminController;
use App\Http\Controllers\QuestionAdminController;
use App\Http\Controllers\QuizUserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminParticipantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RSVPController;
use App\Http\Controllers\TicketDownloadController;



Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Admin donation routes
    Route::get('/donations', [DonationController::class, 'adminIndex'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'adminShow'])->name('donations.show');
    Route::patch('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::patch('/donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');

    // Routes admin pour les remises
    Route::get('/remises', [App\Http\Controllers\RemiseController::class, 'index'])
        ->name('remises.index');
    Route::post('/remises/{id}/status', [App\Http\Controllers\RemiseController::class, 'updateStatus'])
        ->name('remises.updateStatus');
  Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::post('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::post('/events/{event}/publish', [AdminEventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('events.cancel');
    Route::get('/events/{event}/participants', [AdminParticipantController::class, 'index'])->name('events.participants');

    // Routes pour les Quiz Admin
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizAdminController::class, 'index'])->name('index');
        Route::get('/create', [QuizAdminController::class, 'create'])->name('create');
        Route::post('/', [QuizAdminController::class, 'store'])->name('store');
        Route::get('/{quiz}', [QuizAdminController::class, 'show'])->name('show');
        Route::get('/{quiz}/edit', [QuizAdminController::class, 'edit'])->name('edit');
        Route::put('/{quiz}', [QuizAdminController::class, 'update'])->name('update');
        Route::delete('/{quiz}', [QuizAdminController::class, 'destroy'])->name('destroy');
    });

    // Routes pour les Questions Admin
    Route::prefix('quiz/{quiz}/questions')->name('question.')->group(function () {
        Route::get('/', [QuestionAdminController::class, 'index'])->name('index');
        Route::get('/create', [QuestionAdminController::class, 'create'])->name('create');
        Route::post('/', [QuestionAdminController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionAdminController::class, 'show'])->name('show');
        Route::get('/{question}/edit', [QuestionAdminController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuestionAdminController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionAdminController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [QuestionAdminController::class, 'reorder'])->name('reorder');
    });


    Route::resource('categories', AdminCategoryController::class);
    // Admin books routes
    Route::resource('books', AdminBookController::class);
    Route::patch('/books/{book}/toggle-archive', [AdminBookController::class, 'toggleArchive'])->name('books.toggle-archive');
    Route::patch('/books/{book}/toggle-shareable', [AdminBookController::class, 'toggleShareable'])->name('books.toggle-shareable');

        // Routes pour la gestion des topics avec préfixe forum
    Route::prefix('forum')->name('topics.')->group(function () {
        Route::get('/', [ForumAdminController::class, 'index'])->name('index');
        Route::get('/create', [ForumAdminController::class, 'create'])->name('create');
        Route::post('/store', [ForumAdminController::class, 'store'])->name('store');
        Route::get('/{topic}/edit', [ForumAdminController::class, 'edit'])->name('edit');
        Route::put('/{topic}', [ForumAdminController::class, 'update'])->name('update');
        Route::delete('/{topic}', [ForumAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{topic}', [ForumAdminController::class, 'show'])->name('show');
    });


});

// Routes Utilisateur
Route::prefix('/')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');


    // Routes pour les Quiz Utilisateur
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizUserController::class, 'index'])->name('index');
        Route::get('/history', [QuizUserController::class, 'history'])->name('history')->middleware('auth');
        Route::get('/{quiz}', [QuizUserController::class, 'show'])->name('show');
        Route::get('/{quiz}/start', [QuizUserController::class, 'start'])->name('start');
        Route::post('/{quiz}/submit', [QuizUserController::class, 'submit'])->name('submit');
    });




        // Routes pour les posts utilisateur
    Route::prefix('forum')->name('posts.')->group(function () {
        Route::get('/posts', [ForumUserController::class, 'index'])->name('index');
        Route::get('/posts/create', [ForumUserController::class, 'create'])->name('create');
        Route::post('/posts', [ForumUserController::class, 'store'])->name('store');
        Route::get('/posts/{post}', [ForumUserController::class, 'show'])->name('show');
        Route::get('/posts/{post}/edit', [ForumUserController::class, 'edit'])->name('edit');
        Route::put('/posts/{post}', [ForumUserController::class, 'update'])->name('update');
        Route::delete('/posts/{post}', [ForumUserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('forum')->name('comments.')->group(function () {
        Route::post('/posts/{post}/comments', [ForumUserController::class, 'storeComment'])->name('store');
        Route::get('/comments/{comment}/edit', [ForumUserController::class, 'editComment'])->name('edit');
        Route::put('/comments/{comment}', [ForumUserController::class, 'updateComment'])->name('update');
        Route::delete('/comments/{comment}', [ForumUserController::class, 'destroyComment'])->name('destroy');
    });
    Route::prefix('forum')->name('likes.')->group(function () {
        Route::post('/posts/{post}/like', [ForumUserController::class, 'toggle'])->name('toggle');
        Route::get('/posts/{post}/check-like', [ForumUserController::class, 'checkLike'])->name('check');
    });
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

    // Routes pour les remises
    Route::get('/donation/{id}/remise/create', [App\Http\Controllers\RemiseController::class, 'create'])
        ->name('remise.create');
    Route::post('/donation/{id}/remise', [App\Http\Controllers\RemiseController::class, 'store'])
        ->name('remise.store');
    Route::get('/remise/{id}', [App\Http\Controllers\RemiseController::class, 'show'])
        ->name('remise.show');
});
// Profile (existing)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Admin event management (requires auth)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
  
});

// Frontoffice event browsing
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Authenticated interactions: RSVP and ticket download
Route::middleware('auth')->group(function () {
    Route::post('/events/{event:slug}/rsvp', [RSVPController::class, 'store'])->name('events.rsvp');
    Route::get('/events/{event}/tickets/{ticket}/download', TicketDownloadController::class)->name('tickets.download');
});

require __DIR__.'/auth.php';

