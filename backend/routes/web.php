<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/catalog', CatalogController::class)->name('catalog');
Route::get('/about', AboutController::class)->name('about');
Route::get('/works', [WorkController::class, 'index'])->name('works.index');
Route::get('/works/{work:slug}', [WorkController::class, 'show'])->name('works.show');
Route::get('/contacts', [ContactController::class, 'show'])->name('contacts.show');
Route::post('/contacts', [ContactController::class, 'store'])->middleware('throttle:contact-inquiry')->name('contacts.store');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
