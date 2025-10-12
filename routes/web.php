<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionController;

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
});
