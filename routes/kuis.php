<?php

use App\Http\Controllers\HasilKuisController;
use App\Http\Controllers\KuisController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Entitas Kuis & Hasil Kuis
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Katalog & Informasi Kuis
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{id}', [KuisController::class, 'show'])->name('kuis.show');

    // Pengerjaan Kuis & Riwayat (Khusus User Mahasiswa)
    Route::middleware('role:user')->group(function () {
        Route::get('/kuis/{id}/kerjakan', [KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
        Route::post('/kuis/{id}/kerjakan', [KuisController::class, 'simpanJawaban'])->name('kuis.simpan');
        Route::get('/hasil-kuis', [HasilKuisController::class, 'index'])->name('hasil-kuis.index');
    });

    // Detail Evaluasi Skor Kuis
    Route::get('/hasil-kuis/{id}', [HasilKuisController::class, 'show'])->name('hasil-kuis.show');
});
