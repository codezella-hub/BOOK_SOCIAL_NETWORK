<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\ContentModerator;


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications/{notification}/read', [AdminDashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    
    // Route API pour les notifications en temps réel
    Route::get('/notifications/api', [AdminDashboardController::class, 'getNotifications'])->name('notifications.api');
    
    // Admin donation routes
    Route::get('/donations', [DonationController::class, 'adminIndex'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'adminShow'])->name('donations.show');
    Route::patch('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::patch('/donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');

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

Route::prefix('/')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    
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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
