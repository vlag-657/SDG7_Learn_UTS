<?php

use App\Http\Controllers\Admin\ArtikelController as AdminArtikelController;
use App\Http\Controllers\Admin\CallCenterController as AdminCallCenterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\KuisController as AdminKuisController;
use App\Http\Controllers\Admin\PenggunaController as AdminPenggunaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Entitas & Panel Kelola Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Statistik Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Artikel Edukasi
    Route::get('/artikel', [AdminArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/buat', [AdminArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel', [AdminArtikelController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{id}/edit', [AdminArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [AdminArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [AdminArtikelController::class, 'destroy'])->name('artikel.destroy');

    // Manajemen Kuis Interaktif
    Route::get('/kuis', [AdminKuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/buat', [AdminKuisController::class, 'create'])->name('kuis.create');
    Route::post('/kuis', [AdminKuisController::class, 'store'])->name('kuis.store');
    Route::get('/kuis/{id}', [AdminKuisController::class, 'show'])->name('kuis.show');
    Route::get('/kuis/{id}/edit', [AdminKuisController::class, 'edit'])->name('kuis.edit');
    Route::put('/kuis/{id}', [AdminKuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{id}', [AdminKuisController::class, 'destroy'])->name('kuis.destroy');
    Route::get('/kuis/{id}/hasil', [AdminKuisController::class, 'hasil'])->name('kuis.hasil');

    // Pertanyaan & Opsi Jawaban Kuis
    Route::get('/kuis/{id}/pertanyaan/tambah', [AdminKuisController::class, 'tambahPertanyaanForm'])->name('kuis.pertanyaan.create');
    Route::post('/kuis/{id}/pertanyaan', [AdminKuisController::class, 'tambahPertanyaan'])->name('kuis.pertanyaan.store');
    Route::get('/kuis/{id}/pertanyaan/{pertanyaan_id}/edit', [AdminKuisController::class, 'ubahPertanyaanForm'])->name('kuis.pertanyaan.edit');
    Route::put('/kuis/{id}/pertanyaan/{pertanyaan_id}', [AdminKuisController::class, 'ubahPertanyaan'])->name('kuis.pertanyaan.update');
    Route::delete('/kuis/{id}/pertanyaan/{pertanyaan_id}', [AdminKuisController::class, 'hapusPertanyaan'])->name('kuis.pertanyaan.destroy');

    // Moderasi Forum Diskusi
    Route::get('/forum', [AdminForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{id}', [AdminForumController::class, 'show'])->name('forum.show');
    Route::delete('/forum/{id}', [AdminForumController::class, 'hapusPostingan'])->name('forum.destroy');
    Route::delete('/forum/balasan/{id}', [AdminForumController::class, 'hapusBalasan'])->name('forum.balasan.destroy');

    // Manajemen Data Pengguna
    Route::get('/pengguna', [AdminPenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/{id}', [AdminPenggunaController::class, 'show'])->name('pengguna.show');

    // Manajemen Call Center & Layanan Pengaduan
    Route::get('/call-center', [AdminCallCenterController::class, 'index'])->name('call-center.index');
    Route::get('/call-center/{id}', [AdminCallCenterController::class, 'show'])->name('call-center.show');
    Route::put('/call-center/{id}', [AdminCallCenterController::class, 'update'])->name('call-center.update');
    Route::delete('/call-center/{id}', [AdminCallCenterController::class, 'destroy'])->name('call-center.destroy');
});
