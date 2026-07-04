<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ProgramController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('programs', ProgramController::class);
    Route::apiResource('galleries', GalleryController::class);
    Route::apiResource('contacts', ContactController::class);
});

Route::get('programs', [ProgramController::class, 'index']);
Route::get('programs/{program}', [ProgramController::class, 'show']);
Route::get('galleries', [GalleryController::class, 'index']);
Route::get('galleries/{gallery}', [GalleryController::class, 'show']);
Route::post('contacts', [ContactController::class, 'store']);
