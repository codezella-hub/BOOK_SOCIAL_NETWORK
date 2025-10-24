<?php

use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\BookTransactionUserController;

use App\Http\Controllers\FeedbackBookController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionAdminController;
use App\Http\Controllers\QuizAdminController;
use App\Http\Controllers\QuizUserController;
use App\Http\Controllers\RSVPController;
use App\Http\Controllers\TicketDownloadController;

use Illuminate\Http\Request;
use App\Services\ContentModerator;




/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard1');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Tableau de bord
  //  Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard1', [DashboardController::class, 'index'])->name('dashboard1');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getStatsData'])->name('dashboard.chart-data');
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications/{notification}/read', [AdminDashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Route API pour les notifications en temps réel
    Route::get('/notifications/api', [AdminDashboardController::class, 'getNotifications'])->name('notifications.api');

    // Admin donation routes
    // Dons (Admin)
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

        // Routes pour la gestion des topics avec préfixe forum
    Route::prefix('forum')->name('topics.')->group(function () {
        Route::get('/', [ForumAdminController::class, 'index'])->name('index'); // /admin/forum
        Route::get('/create', [ForumAdminController::class, 'create'])->name('create');
        Route::post('/store', [ForumAdminController::class, 'store'])->name('store');
        Route::get('/{topic}/edit', [ForumAdminController::class, 'edit'])->name('edit');
        Route::put('/{topic}', [ForumAdminController::class, 'update'])->name('update');
        Route::delete('/{topic}', [ForumAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{topic}', [ForumAdminController::class, 'show'])->name('show');
    });

    // Routes pour la gestion des reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ForumAdminController::class, 'indexR'])->name('index'); // /admin/reports
        Route::get('/{report}', [ForumAdminController::class, 'showR'])->name('show');
        Route::delete('/{report}', [ForumAdminController::class, 'destroyR'])->name('destroy');
        Route::delete('/{report}/delete-post', [ForumAdminController::class, 'deletePost'])->name('deletePost');
        Route::delete('/{report}/ignore', [ForumAdminController::class, 'ignore'])->name('ignore');
    });


});

/*
|--------------------------------------------------------------------------
| FRONT UTILISATEURS
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'home'])->name('user.home');
Route::name('user.')->group(function () {


    // Quiz utilisateur
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizUserController::class, 'index'])->name('index');
        Route::get('/history', [QuizUserController::class, 'history'])->middleware('auth')->name('history');
        Route::get('/{book}', [QuizUserController::class, 'byBook'])->name('byBook');
        Route::get('/{book}/{quiz}', [QuizUserController::class, 'show'])->name('show');
        Route::get('/{book}/{quiz}/start', [QuizUserController::class, 'start'])->name('start');
        Route::post('/{book}/{quiz}/submit', [QuizUserController::class, 'submit'])->name('submit');
    });




        // Routes pour les posts utilisateur
// === POSTS ===
Route::prefix('forum')->name('posts.')->group(function () {
    Route::get('/posts', [ForumUserController::class, 'index'])->name('index');
    Route::get('/posts/create', [ForumUserController::class, 'create'])->name('create');
    Route::post('/posts', [ForumUserController::class, 'store'])
        ->middleware('moderate')
        ->name('store');
    Route::get('/posts/{post}', [ForumUserController::class, 'show'])->name('show');
    Route::get('/posts/{post}/edit', [ForumUserController::class, 'edit'])->name('edit');
    Route::put('/posts/{post}', [ForumUserController::class, 'update'])
        ->middleware('moderate')
        ->name('update');
    Route::delete('/posts/{post}', [ForumUserController::class, 'destroy'])->name('destroy');
});
// === COMMENTS ===
    Route::prefix('forum')->name('comments.')->group(function () {
        Route::post('/posts/{post}/comments', [ForumUserController::class, 'storeComment'])
            ->middleware('moderate')
            ->name('store');
        Route::get('/comments/{comment}/edit', [ForumUserController::class, 'editComment'])->name('edit');
        Route::put('/comments/{comment}', [ForumUserController::class, 'updateComment'])
            ->middleware('moderate')
            ->name('update');
        Route::delete('/comments/{comment}', [ForumUserController::class, 'destroyComment'])->name('destroy');
        Route::post('/comments/{comment}/replies', [ForumUserController::class, 'storeReply'])
            ->middleware('moderate')
            ->name('reply.store');
    });


// === LIKES ===
    Route::prefix('forum')->name('likes.')->group(function () {
        Route::post('/posts/{post}/like', [ForumUserController::class, 'toggle'])->name('toggle');
        Route::get('/posts/{post}/check-like', [ForumUserController::class, 'checkLike'])->name('check');
    });

// === COMMENT LIKES ===
    Route::prefix('forum')->name('comment_likes.')->group(function () {
        Route::post('/comments/{comment}/like', [ForumUserController::class, 'toggleCommentLike'])
            ->name('toggle');            // user.comment_likes.toggle
        Route::get('/comments/{comment}/check-like', [ForumUserController::class, 'checkCommentLike'])
            ->name('check');             // user.comment_likes.check
    });


// === REPORTS ===
    Route::prefix('forum')->name('reports.')->group(function () {
        Route::post('/posts/{post}/report', [ForumUserController::class, 'storeReport'])->name('store');
        Route::get('/posts/{post}/check-report', [ForumUserController::class, 'checkReport'])->name('check');
    });
});
/*
|--------------------------------------------------------------------------
| LIVRES UTILISATEURS
|--------------------------------------------------------------------------
*/


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






Route::post('/moderate/live', function (Request $request, ContentModerator $moderator) {
    $validated = $request->validate([
        'text' => 'required|string|max:2000',
    ]);

    $res = $moderator->moderate($validated['text']);

    return response()->json([
        'clean'  => $res['clean'],
        'toxic'  => $res['toxic'],
        'scores' => $res['scores'],
    ]);
})->name('moderate.live');

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
Route::get('/events/nearby', [EventController::class, 'nearby'])->name('events.nearby');

Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    Route::post('/events/{event:slug}/rsvp', [RSVPController::class, 'store'])->name('events.rsvp');
    Route::get('/events/{event}/tickets/{ticket}/download', TicketDownloadController::class)->name('tickets.download');

    // Routes pour le chatbot IA
    Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/ask', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');
    Route::post('/chatbot/similar-books', [App\Http\Controllers\ChatbotController::class, 'getSimilarBooks'])->name('chatbot.similar-books');
    Route::post('/chatbot/author-info', [App\Http\Controllers\ChatbotController::class, 'getAuthorInfo'])->name('chatbot.author-info');
    Route::post('/chatbot/recommendations', [App\Http\Controllers\ChatbotController::class, 'getRecommendationsByGenre'])->name('chatbot.recommendations');
    Route::get('/chatbot/donation/{donation}', [App\Http\Controllers\ChatbotController::class, 'fromDonation'])->name('chatbot.donation');
});
// Routes pour les transactions de livres
Route::middleware('auth')->group(function () {

    Route::post('/books/{book}/borrow-request', [BookTransactionUserController::class, 'borrowRequest'])->name('user.books.borrow-request');


    Route::get('/my-borrowing-history', [BookTransactionUserController::class, 'myBorrowingHistory'])->name('user.books.borrowing-history');


    Route::get('/my-lending-requests', [BookTransactionUserController::class, 'myLendingRequests'])->name('user.books.lending-requests');


    Route::patch('/transactions/{transaction}/approve', [BookTransactionUserController::class, 'approveRequest'])->name('user.transactions.approve');
    Route::patch('/transactions/{transaction}/reject', [BookTransactionUserController::class, 'rejectRequest'])->name('user.transactions.reject');
    Route::patch('/transactions/{transaction}/mark-borrowed', [BookTransactionUserController::class, 'markAsBorrowed'])->name('user.transactions.mark-borrowed');
    Route::patch('/transactions/{transaction}/mark-returned', [BookTransactionUserController::class, 'markAsReturned'])->name('user.transactions.mark-returned');
    Route::patch('/transactions/{transaction}/confirm-return', [BookTransactionUserController::class, 'confirmReturn'])->name('user.transactions.confirm-return');
    Route::delete('/transactions/{transaction}/cancel', [BookTransactionUserController::class, 'cancelRequest'])->name('user.transactions.cancel');
});


Route::prefix('feedback')->name('user.feedback.')->group(function () {
    Route::get('/transaction/{transaction}/create', [FeedbackBookController::class, 'create'])->name('create');
    Route::post('/transaction/{transaction}', [FeedbackBookController::class, 'store'])->name('store');
    Route::get('/{feedback}/edit', [FeedbackBookController::class, 'edit'])->name('edit');
    Route::put('/{feedback}', [FeedbackBookController::class, 'update'])->name('update');
    Route::delete('/{feedback}', [FeedbackBookController::class, 'destroy'])->name('destroy');
    Route::get('/can-give/{transaction}', [FeedbackBookController::class, 'canGiveFeedback'])->name('can-give');
});

// Route publique pour voir les feedbacks d'un livre
Route::get('/books/{book}/feedbacks', [FeedbackBookController::class, 'index'])->name('books.feedbacks');
// Auth routes
require __DIR__ . '/auth.php';
