<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ForumAdminController;
use App\Http\Controllers\ForumUserController;
use App\Http\Controllers\HomeController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

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


Route::middleware(['auth'])->group(function () {
    
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
