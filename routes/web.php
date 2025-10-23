<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminParticipantController;
use App\Http\Controllers\Admin\AIQuestionController;
use App\Http\Controllers\BookUserController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionAdminController;
use App\Http\Controllers\QuizAdminController;
use App\Http\Controllers\QuizUserController;
use App\Http\Controllers\RSVPController;
use App\Http\Controllers\TicketDownloadController;

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Tableau de bord
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Dons (Admin)
    Route::get('/donations', [DonationController::class, 'adminIndex'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'adminShow'])->name('donations.show');
    Route::patch('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::patch('/donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');

    // Événements
    Route::resource('events', AdminEventController::class)->except(['show']);
    Route::post('/events/{event}/publish', [AdminEventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('events.cancel');
    Route::get('/events/{event}/participants', [AdminParticipantController::class, 'index'])->name('events.participants');

    // Quiz
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizAdminController::class, 'index'])->name('index');
        Route::get('/fetch', [QuizAdminController::class, 'fetch'])->name('fetch');
        Route::get('/create', [QuizAdminController::class, 'create'])->name('create');
        Route::post('/', [QuizAdminController::class, 'store'])->name('store');

        // Questions d’un quiz
        Route::prefix('{quiz}/questions')->name('question.')->group(function () {
            // Génération IA
            Route::get('/generate', [AIQuestionController::class, 'showForm'])->name('generate.form');
            Route::post('/generate', [AIQuestionController::class, 'generate'])->name('generate');
            Route::post('/generate/preview', [AIQuestionController::class, 'preview'])->name('generate.preview');

            // CRUD Questions
            Route::get('/', [QuestionAdminController::class, 'index'])->name('index');
            Route::get('/create', [QuestionAdminController::class, 'create'])->name('create');
            Route::post('/', [QuestionAdminController::class, 'store'])->name('store');
            Route::get('/{question}/edit', [QuestionAdminController::class, 'edit'])->name('edit');
            Route::put('/{question}', [QuestionAdminController::class, 'update'])->name('update');
            Route::delete('/{question}', [QuestionAdminController::class, 'destroy'])->name('destroy');
        });

        Route::get('/{quiz}/edit', [QuizAdminController::class, 'edit'])->name('edit');
        Route::put('/{quiz}', [QuizAdminController::class, 'update'])->name('update');
        Route::delete('/{quiz}', [QuizAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{book}/{quiz}', [QuizAdminController::class, 'show'])->name('show');
    });

    // Livres & catégories
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('books', AdminBookController::class);
    Route::patch('/books/{book}/toggle-archive', [AdminBookController::class, 'toggleArchive'])->name('books.toggle-archive');
    Route::patch('/books/{book}/toggle-shareable', [AdminBookController::class, 'toggleShareable'])->name('books.toggle-shareable');

    // Forum Admin
    Route::resource('forum', ForumAdminController::class)->parameters(['forum' => 'topic']);

    // Healthcheck IA
    Route::get('/ai/health', function () {
        $resp = Http::withToken(env('AI_API_KEY'))
            ->timeout((int) env('AI_TIMEOUT', 60))
            ->post(rtrim(env('AI_API_BASE'), '/') . '/chat/completions', [
                'model' => env('AI_MODEL'),
                'messages' => [['role' => 'user', 'content' => 'Réponds "OK".']],
                'max_tokens' => 5,
            ]);

        return response()->json([
            'ok'      => $resp->ok(),
            'status'  => $resp->status(),
            'content' => optional($resp->json())['choices'][0]['message']['content'] ?? $resp->body(),
        ], $resp->ok() ? 200 : 500);
    })->name('ai.health');
});

/*
|--------------------------------------------------------------------------
| FRONT UTILISATEURS
|--------------------------------------------------------------------------
*/
Route::name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');

    // Quiz utilisateur
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizUserController::class, 'index'])->name('index');
        Route::get('/history', [QuizUserController::class, 'history'])->middleware('auth')->name('history');
        Route::get('/{book}', [QuizUserController::class, 'byBook'])->name('byBook');
        Route::get('/{book}/{quiz}', [QuizUserController::class, 'show'])->name('show');
        Route::get('/{book}/{quiz}/start', [QuizUserController::class, 'start'])->name('start');
        Route::post('/{book}/{quiz}/submit', [QuizUserController::class, 'submit'])->name('submit');
    });

    // Forum utilisateur
    Route::prefix('forum')->group(function () {
        Route::resource('posts', ForumUserController::class);
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::post('/{post}/comments', [ForumUserController::class, 'storeComment'])->name('store');
            Route::get('/{comment}/edit', [ForumUserController::class, 'editComment'])->name('edit');
            Route::put('/{comment}', [ForumUserController::class, 'updateComment'])->name('update');
            Route::delete('/{comment}', [ForumUserController::class, 'destroyComment'])->name('destroy');
        });
        Route::prefix('likes')->name('likes.')->group(function () {
            Route::post('/posts/{post}/like', [ForumUserController::class, 'toggle'])->name('toggle');
            Route::get('/posts/{post}/check-like', [ForumUserController::class, 'checkLike'])->name('check');
        });
    });
});

/*
|--------------------------------------------------------------------------
| LIVRES UTILISATEURS
|--------------------------------------------------------------------------
*/
Route::get('/books', [BookUserController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookUserController::class, 'show'])->name('books.show');

// "Mes livres" utilisateur (auth)
Route::middleware('auth')->group(function () {
    Route::resource('my-books', BookUserController::class)->names('user.books');
    Route::patch('/my-books/{book}/toggle-shareable', [BookUserController::class, 'toggleShareable'])->name('user.books.toggle-shareable');
    Route::patch('/my-books/{book}/toggle-archive', [BookUserController::class, 'toggleArchive'])->name('user.books.toggle-archive');
});

/*
|--------------------------------------------------------------------------
| DONS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('donations', DonationController::class)->names('user.donations');
});

/*
|--------------------------------------------------------------------------
| PROFIL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ÉVÉNEMENTS
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    Route::post('/events/{event:slug}/rsvp', [RSVPController::class, 'store'])->name('events.rsvp');
    Route::get('/events/{event}/tickets/{ticket}/download', TicketDownloadController::class)->name('tickets.download');
});

// Auth routes
require __DIR__ . '/auth.php';
