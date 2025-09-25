<?php

use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\PublishedController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::controller(NoteController::class)->group(function (): void {
        Route::post('/notes', 'store');
        Route::get('/notes', 'index');
    });
});

Route::get('/published/{slug}', [PublishedController::class, 'show']);
