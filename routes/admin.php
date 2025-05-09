<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Middleware\Admin\AdminAuthMiddleware;
use App\Http\Middleware\Admin\AuthLoginMiddleware;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Admin;

Route::middleware(AuthLoginMiddleware::class)->group(function () {

    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');


    Route::get('/forgot-password', function () {
        return view('admin.auth.forgot-password');
    })->name('forgot-password.view');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot-password.post');
    Route::get('reset-password/{token}', [AuthController::class, 'resetPasswordView'])->name('reset-password.view');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.post');
});
Route::middleware(AdminAuthMiddleware::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('change-password', [ProfileController::class, 'changePasswordView'])->name('profile.change-password');
        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    });
    Route::prefix('admin')->group(function () {
        Route::get('index', Admin::class)->name('admin.index');
    });
});
