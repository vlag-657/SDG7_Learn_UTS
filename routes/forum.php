<?php

use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Entitas Forum Diskusi & Balasan
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/buat', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{id}/balas', [ForumController::class, 'balas'])->name('forum.balas');
});
