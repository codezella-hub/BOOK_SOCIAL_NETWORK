<?php

use App\Http\Controllers\AdminDashboardController;
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
});

Route::prefix('/')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
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
