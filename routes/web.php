<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Middleware\Admin\AdminAuthMiddleware;
use App\Http\Middleware\Admin\LoginCheckMiddleware;
use App\Livewire\Admin\AdminList;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Lubusin\Decomposer\Controllers\DecomposerController;


Route::get('/decompose',[DecomposerController::class,'index']);


Route::get('/login',function(){
    dd(1);
})->name('login');

Route::get('/test-session', function () {
    session(['test_key' => 'test_value']);
    return session('test_key');
});