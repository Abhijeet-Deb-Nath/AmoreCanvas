<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemoryLaneController;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoveLetterController;

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

// Email Verification Routes (outside auth middleware)
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verify.resend');
Route::get('/verify-new-email/{token}/{userId}', [ProfileController::class, 'verifyNewEmail'])->name('verify.new-email');

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
    
    // Profile Management Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update-name', [ProfileController::class, 'updateName'])->name('profile.update-name');
    Route::put('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Memory Lane Routes
    Route::get('/memory-lane', [MemoryLaneController::class, 'index'])->name('memory-lane.index');
    Route::get('/memory-lane/create', [MemoryLaneController::class, 'create'])->name('memory-lane.create');
    Route::post('/memory-lane', [MemoryLaneController::class, 'store'])->name('memory-lane.store');
    Route::post('/memory-lane/from-dream/{dreamId}', [MemoryLaneController::class, 'storeFromDream'])->name('memory-lane.store-from-dream');
    Route::get('/memory-lane/{id}', [MemoryLaneController::class, 'show'])->name('memory-lane.show');
    Route::get('/memory-lane/{id}/edit', [MemoryLaneController::class, 'edit'])->name('memory-lane.edit');
    Route::put('/memory-lane/{id}', [MemoryLaneController::class, 'update'])->name('memory-lane.update');
    Route::delete('/memory-lane/{id}', [MemoryLaneController::class, 'destroy'])->name('memory-lane.destroy');
    
    // Memory Review Routes
    Route::post('/memory-lane/{memoryId}/review', [MemoryLaneController::class, 'storeReview'])->name('memory-lane.review.store');
    Route::put('/memory-review/{reviewId}', [MemoryLaneController::class, 'updateReview'])->name('memory-lane.review.update');
    Route::delete('/memory-review/{reviewId}', [MemoryLaneController::class, 'destroyReview'])->name('memory-lane.review.destroy');
    
    // Shared Dreams Routes
    Route::get('/shared-dreams', [DreamController::class, 'index'])->name('dreams.index');
    Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
    Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
    Route::get('/dreams/{id}', [DreamController::class, 'show'])->name('dreams.show');
    Route::get('/dreams/{id}/edit', [DreamController::class, 'edit'])->name('dreams.edit');
    Route::put('/dreams/{id}', [DreamController::class, 'update'])->name('dreams.update');
    Route::delete('/dreams/{id}', [DreamController::class, 'destroy'])->name('dreams.destroy');
    Route::post('/dreams/{id}/validate', [DreamController::class, 'validate'])->name('dreams.validate');
    
    // Destiny Date Planning Routes
    Route::get('/dreams/{id}/plan-destiny', [DreamController::class, 'planDestiny'])->name('dreams.plan-destiny');
    Route::post('/dreams/{id}/propose-date', [DreamController::class, 'proposeDate'])->name('dreams.propose-date');
    Route::post('/dreams/{dreamId}/accept-date/{negotiationId}', [DreamController::class, 'acceptDate'])->name('dreams.accept-date');
    Route::post('/dreams/{dreamId}/reject-date/{negotiationId}', [DreamController::class, 'rejectDate'])->name('dreams.reject-date');
    Route::post('/dreams/{dreamId}/edit-date/{negotiationId}', [DreamController::class, 'editDate'])->name('dreams.edit-date');
    
    // Bucket List Routes
    Route::get('/bucket-list', [DreamController::class, 'bucketList'])->name('bucket-list.index');
    Route::get('/bucket-list/cherished-memories', [DreamController::class, 'cherishedMemories'])->name('dreams.cherished-memories');
    
    // Reschedule Routes
    Route::get('/dreams/{id}/request-reschedule', [DreamController::class, 'requestReschedule'])->name('dreams.request-reschedule');
    Route::post('/dreams/{id}/submit-reschedule', [DreamController::class, 'submitReschedule'])->name('dreams.submit-reschedule');
    
    // Remove from Bucket List Routes
    Route::post('/dreams/{id}/request-remove', [DreamController::class, 'requestRemove'])->name('dreams.request-remove');
    Route::post('/dreams/{dreamId}/confirm-remove/{negotiationId}', [DreamController::class, 'confirmRemove'])->name('dreams.confirm-remove');
    
    // Cherished Memories Actions
    Route::post('/dreams/{id}/mark-fulfilled', [DreamController::class, 'markFulfilled'])->name('dreams.mark-fulfilled');
    Route::post('/dreams/{id}/mark-missed', [DreamController::class, 'markMissed'])->name('dreams.mark-missed');
    
    // Lived in the Dream Routes
    Route::get('/lived-in-the-dream', [DreamController::class, 'lived'])->name('dreams.lived');
    Route::get('/dreams/{id}/create-memory', [DreamController::class, 'createMemoryFromDream'])->name('dreams.create-memory');
    
    // Love Letter Routes
    Route::get('/love-letters', [LoveLetterController::class, 'index'])->name('love-letters.index');
    Route::get('/love-letters/create', [LoveLetterController::class, 'create'])->name('love-letters.create');
    Route::post('/love-letters', [LoveLetterController::class, 'store'])->name('love-letters.store');
    Route::get('/love-letters/{id}', [LoveLetterController::class, 'show'])->name('love-letters.show');
    Route::post('/love-letters/{id}/mark-as-read', [LoveLetterController::class, 'markAsRead'])->name('love-letters.mark-as-read');
    Route::get('/love-letters/{id}/download', [LoveLetterController::class, 'download'])->name('love-letters.download');
    Route::post('/love-letters/{id}/add-to-memory-lane', [LoveLetterController::class, 'addToMemoryLane'])->name('love-letters.add-to-memory-lane');
    Route::delete('/love-letters/{id}', [LoveLetterController::class, 'destroy'])->name('love-letters.destroy');
});
