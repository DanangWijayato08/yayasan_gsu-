<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\HomepageSectionController;
use App\Http\Controllers\Api\Admin\SingletonController;
use App\Http\Controllers\Api\Admin\UploadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\Public\ContentController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login');

Route::get('public/home', [ContentController::class, 'home']);
Route::get('public/layout', [ContentController::class, 'layout']);
Route::get('site-settings', [ContentController::class, 'siteSettings']);
Route::get('homepage', [ContentController::class, 'homepage']);
Route::get('about', [ContentController::class, 'about']);
Route::get('programs', [ProgramController::class, 'index']);
Route::get('programs/featured', [ProgramController::class, 'featured']);
Route::get('programs/{slug}', [ProgramController::class, 'showBySlug']);
Route::get('galleries', [GalleryController::class, 'index']);
Route::get('galleries/{gallery}', [GalleryController::class, 'show']);
Route::get('donation-settings', [ContentController::class, 'donationSettings']);
Route::get('contact-settings', [ContentController::class, 'contactSettings']);
Route::get('footer', [ContentController::class, 'footer']);
Route::post('contact-messages', [ContactController::class, 'store'])->middleware('throttle:contact-submission');
Route::post('contacts', [ContactController::class, 'store'])->middleware('throttle:contact-submission');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/dashboard', DashboardController::class);
        Route::post('/upload', UploadController::class);

        Route::get('/site-settings', [SingletonController::class, 'show'])->defaults('type', 'site-settings');
        Route::put('/site-settings', [SingletonController::class, 'update'])->defaults('type', 'site-settings');
        Route::get('/about', [SingletonController::class, 'show'])->defaults('type', 'about');
        Route::put('/about', [SingletonController::class, 'update'])->defaults('type', 'about');
        Route::get('/donation-settings', [SingletonController::class, 'show'])->defaults('type', 'donation-settings');
        Route::put('/donation-settings', [SingletonController::class, 'update'])->defaults('type', 'donation-settings');
        Route::get('/contact-settings', [SingletonController::class, 'show'])->defaults('type', 'contact-settings');
        Route::put('/contact-settings', [SingletonController::class, 'update'])->defaults('type', 'contact-settings');
        Route::get('/footer-settings', [SingletonController::class, 'show'])->defaults('type', 'footer-settings');
        Route::put('/footer-settings', [SingletonController::class, 'update'])->defaults('type', 'footer-settings');

        Route::apiResource('homepage-sections', HomepageSectionController::class)->except(['show']);
        Route::get('programs', [ProgramController::class, 'adminIndex']);
        Route::apiResource('programs', ProgramController::class)->except(['index']);
        Route::get('galleries', [GalleryController::class, 'adminIndex']);
        Route::apiResource('galleries', GalleryController::class)->except(['index']);
        Route::apiResource('contact-messages', ContactController::class)
            ->except(['store'])
            ->parameters(['contact-messages' => 'contact']);
    });
});
