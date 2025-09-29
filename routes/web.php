<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
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

Route::prefix('/')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    
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
