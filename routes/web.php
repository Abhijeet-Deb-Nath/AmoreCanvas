<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemoryLaneController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Connection Routes
    Route::post('/send-invitation-email', [ConnectionController::class, 'sendInvitationByEmail'])->name('send.invitation.email');
    Route::post('/send-invitation/{user}', [ConnectionController::class, 'sendInvitation'])->name('send.invitation');
    Route::post('/accept-invitation/{connection}', [ConnectionController::class, 'acceptInvitation'])->name('accept.invitation');
    Route::post('/decline-invitation/{connection}', [ConnectionController::class, 'declineInvitation'])->name('decline.invitation');
    
    // Shared Canvas (Couple Space)
    Route::get('/shared-canvas', [ConnectionController::class, 'sharedCanvas'])->name('shared.canvas');
    
    // Memory Lane Routes
    Route::get('/memory-lane', [MemoryLaneController::class, 'index'])->name('memory-lane.index');
    Route::get('/memory-lane/create', [MemoryLaneController::class, 'create'])->name('memory-lane.create');
    Route::post('/memory-lane', [MemoryLaneController::class, 'store'])->name('memory-lane.store');
    Route::get('/memory-lane/{id}', [MemoryLaneController::class, 'show'])->name('memory-lane.show');
    Route::get('/memory-lane/{id}/edit', [MemoryLaneController::class, 'edit'])->name('memory-lane.edit');
    Route::put('/memory-lane/{id}', [MemoryLaneController::class, 'update'])->name('memory-lane.update');
    Route::delete('/memory-lane/{id}', [MemoryLaneController::class, 'destroy'])->name('memory-lane.destroy');
    
    // Memory Review Routes
    Route::post('/memory-lane/{memoryId}/review', [MemoryLaneController::class, 'storeReview'])->name('memory-lane.review.store');
    Route::put('/memory-review/{reviewId}', [MemoryLaneController::class, 'updateReview'])->name('memory-lane.review.update');
    Route::delete('/memory-review/{reviewId}', [MemoryLaneController::class, 'destroyReview'])->name('memory-lane.review.destroy');
});
