<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Meeting routes
Route::prefix('meetings')->name('meetings.')->group(function () {
    Route::get('create', [MeetingController::class, 'create'])->name('create');
    Route::post('/', [MeetingController::class, 'store'])->name('store');
    Route::get('{meeting}/lobby', [MeetingController::class, 'lobby'])->name('lobby');
    Route::get('{meeting}', [MeetingController::class, 'join'])->name('join');
    
    // WebRTC Signaling (PHP-based)
    Route::post('{meeting}/signal', [App\Http\Controllers\SignalingController::class, 'signal'])->name('signal');
    Route::get('{meeting}/poll', [App\Http\Controllers\SignalingController::class, 'poll'])->name('poll');
    Route::post('{meeting}/leave', [App\Http\Controllers\SignalingController::class, 'leave'])->name('leave');
    
    // Participant management
    Route::post('{meeting}/participants/status', [App\Http\Controllers\ParticipantController::class, 'updateStatus'])->name('participants.status');
    Route::get('{meeting}/participants', [App\Http\Controllers\ParticipantController::class, 'list'])->name('participants.list');
});

// Authentication routes
Route::get('/auth/google', [App\Http\Controllers\AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Network ping endpoint
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});
