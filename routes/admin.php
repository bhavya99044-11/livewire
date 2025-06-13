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
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\BannerController;
Route::get('/test', function () {
    App::setLocale('es');
    return text('test_key', 'Hello world');
});

Route::middleware(LoginCheckMiddleware::class)->group(function () {

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
    ROute::get('vendors/{vendor_Id}/createProduct',[vendorController::class, 'createProduct'])->name('vendors.create-product');
    ROute::get('vendors/{vendor_Id}/product-list',[ProductController::class, 'productList'])->name('vendors.product-list');

    Route::middleware(['route-access:vendor'])->prefix('vendors')->group(function () {
        Route::get('list/data', [VendorController::class, 'showData'])->name('vendors.data');
        Route::post('/update-status', [VendorController::class, 'updateStatus'])->name('vendors.update-status');
        Route::post('/update-action', [VendorController::class, 'updateAction'])->name('vendors.update-action');
    });
    Route::resource('domains', DomainController::class);
    Route::resource('/vendor/{vendor_id}/products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/products/search/vendor', [\App\Http\Controllers\Admin\ProductController::class, 'searchVendor'])->name('products.search-vendor');
    Route::prefix('/vendor/{vendor_id?}/products')->group(function () {
        // Create
        Route::post('store-step-1', [ProductController::class, 'productStepOne'])->name('products.store-step-1');
        Route::post('store-step-2', [ProductController::class, 'productStepTwo'])->name('products.store-step-2');

        // Update
        Route::put('{product}/update-step-1', [ProductController::class, 'productStepOne'])->name('products.update-step-1');
        Route::put('{product}/update-step-2', [ProductController::class, 'productStepTwo'])->name('products.update-step-2');
        
        // Edit form
        Route::get('{product_id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::get('{product_id}/show', [ProductController::class, 'show'])->name('products.show');
    });
    Route::delete('/product/{product_id}/delete', [ProductController::class, 'delete'])->name('products.destroy');
    Route::post('product/update-actions', [ProductController::class, 'updateActions'])->name('products.update-actions');
    Route::get('product/list', [ProductController::class, 'list'])->name('products.list');
    Route::get('cms/{slug}',[CmsController::class,'index'])->name('cms')->where('slug', '.*');;
    Route::post('cms/{slug}',[CmsController::class,'create'])->name('cms');

    Route::resource('faqs',FaqController::class);
    Route::post('faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');

    Route::resource('banners',BannerController::class);
    Route::put('/banners/{banner}/status', [BannerController::class, 'statusUpdate'])->name('banners.status');
});
