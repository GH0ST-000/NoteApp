<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('notes', NoteController::class);
    Route::resource('groups', GroupController::class);
});
Route::get('/p/{slug}', [NoteController::class, 'showPublished'])->name('notes.published');
Route::get('/g/{slug}', [GroupController::class, 'showPublished'])->name('groups.published');

require __DIR__.'/auth.php';
