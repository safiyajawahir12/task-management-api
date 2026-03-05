<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Tasks — trashed must come BEFORE {id}
    Route::get('/tasks/trashed',       [TaskController::class, 'trashed']);
    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);

    Route::get('/tasks',         [TaskController::class, 'index']);
    Route::post('/tasks',        [TaskController::class, 'store']);
    Route::get('/tasks/{id}',    [TaskController::class, 'show']);
    Route::put('/tasks/{id}',    [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});
