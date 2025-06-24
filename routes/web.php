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
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;



Route::get('/login',function(){
    dd(1);
})->name('login');


Route::get('/test-session', function () {
   return view('session');
});

Route::post('test',[TestController::class,'test'])->name('test');