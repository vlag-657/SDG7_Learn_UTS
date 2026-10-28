<?php

use App\Http\Controllers\CallCenterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Entitas Call Center (Pusat Bantuan & Pengaduan)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/call-center', [CallCenterController::class, 'index'])->name('call-center.index');
    Route::get('/call-center/buat', [CallCenterController::class, 'create'])->name('call-center.create');
    Route::post('/call-center', [CallCenterController::class, 'store'])->name('call-center.store');
    Route::get('/call-center/{id}', [CallCenterController::class, 'show'])->name('call-center.show');
    Route::get('/call-center/{id}/edit', [CallCenterController::class, 'edit'])->name('call-center.edit');
    Route::put('/call-center/{id}', [CallCenterController::class, 'update'])->name('call-center.update');
    Route::delete('/call-center/{id}', [CallCenterController::class, 'destroy'])->name('call-center.destroy');
});
