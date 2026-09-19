<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PageImageController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WorkController;
use App\Http\Controllers\Admin\WorkPhotoController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->middleware('throttle:admin-login')->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('page-images', [PageImageController::class, 'index'])->name('page-images.index');
        Route::put('page-images', [PageImageController::class, 'update'])->name('page-images.update');
        Route::resource('catalogs', CatalogController::class)->except(['show']);
        Route::resource('works', WorkController::class)->except(['show']);
        Route::post('works/{work}/photos', [WorkPhotoController::class, 'store'])->name('works.photos.store');
        Route::post('works/{work}/photos/{photo}/move', [WorkPhotoController::class, 'move'])->name('works.photos.move');
        Route::delete('works/{work}/photos/{photo}', [WorkPhotoController::class, 'destroy'])->name('works.photos.destroy');
        Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
        Route::put('seo/globals', [SeoController::class, 'updateGlobals'])->name('seo.globals');
        Route::get('seo/{page}', [SeoController::class, 'edit'])->name('seo.edit');
        Route::put('seo/{page}', [SeoController::class, 'update'])->name('seo.update');
        Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::put('inquiries/{inquiry}', [InquiryController::class, 'markRead'])->name('inquiries.read');
        Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
        Route::resource('documents', DocumentController::class)->except(['show']);
        Route::get('redirects', [RedirectController::class, 'index'])->name('redirects.index');
        Route::post('redirects', [RedirectController::class, 'store'])->name('redirects.store');
        Route::delete('redirects/{redirect}', [RedirectController::class, 'destroy'])->name('redirects.destroy');
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
