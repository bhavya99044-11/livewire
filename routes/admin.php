<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Middleware\Admin\AdminAuthMiddleware;
use App\Http\Middleware\Admin\AuthLoginMiddleware;
use App\Livewire\Admin\AdminList;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/test', function () {
    App::setLocale('es');
    return text('test_key', 'Hello world');
});

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
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('index', AdminList::class)->name('admin.index');
    });
    Route::middleware(['role:super_admin'])->resource('permissions', PermissionController::class);
    Route::middleware(['route-access:vendor'])->resource('vendors', VendorController::class);
    Route::middleware(['route-access:vendor'])->prefix('vendors')->group(function(){
        Route::get('list/data',[ VendorController::class,'showData'])->name('vendors.data');
        Route::post('/update-status', [VendorController::class, 'updateStatus'])->name('vendors.update-status');
        Route::post('/update-action', [VendorController::class, 'updateAction'])->name('vendors.update-action');
    });
    Route::resource('domains', DomainController::class);
});
