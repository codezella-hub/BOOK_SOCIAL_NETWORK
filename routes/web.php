<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizAdminController;
use App\Http\Controllers\QuestionAdminController;
use App\Http\Controllers\QuizUserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Routes Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

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
});

// Routes d'authentification et paramètres
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
