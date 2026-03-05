<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


// Guest routes: login, register
// | Admin routes: dashboard, task CRUD (role:admin middleware)
// | User routes: dashboard, mark complete, edit (role:user middleware)
// ── Root ───────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth routes (guests only) ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Logout ─────────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Admin routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',     [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/tasks',        [AdminController::class, 'store'])->name('admin.tasks.store');
    Route::put('/tasks/{id}',    [AdminController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/tasks/{id}', [AdminController::class, 'destroy'])->name('admin.tasks.destroy');
});

// ── User routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard',                 [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/user/tasks/{id}/complete', [UserController::class, 'markComplete'])->name('user.tasks.complete');
    Route::put('/user/tasks/{id}',           [UserController::class, 'update'])->name('user.tasks.update');
});
