<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::prefix('auth')->group(function () {
    // User Login
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum'])->prefix('/')->group(function () {
    // User routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
    });

    Route::prefix('diagnoses')->group(function () {
        Route::post('/', [DiagnosisController::class, 'getDiagnosis']);
        Route::get('/history', [DiagnosisController::class, 'history']);
    });

    
});
