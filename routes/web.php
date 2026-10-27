<?php

use App\Http\Controllers\Web\Admin\ArtikelController as AdminArtikelController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Web\Admin\KuisController as AdminKuisController;
use App\Http\Controllers\Web\Admin\PenggunaController as AdminPenggunaController;
use App\Http\Controllers\Web\ArtikelController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ForumController;
use App\Http\Controllers\Web\HasilKuisController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\KuisController;
use App\Http\Controllers\Web\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (MVC Blade Architecture)
|--------------------------------------------------------------------------
*/

// Beranda Publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autentikasi (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (Harus Login)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Artikel (Publik)
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');

// Area Harus Login (User & Admin)
Route::middleware('auth')->group(function () {

    // Profil Pengguna
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'ubahPassword'])->name('profil.password');

    // Kuis (Daftar & Info)
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{id}', [KuisController::class, 'show'])->name('kuis.show');

    // Kerjakan Kuis & Riwayat (Khusus User)
    Route::middleware('role:user')->group(function () {
        Route::get('/kuis/{id}/kerjakan', [KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
        Route::post('/kuis/{id}/kerjakan', [KuisController::class, 'simpanJawaban'])->name('kuis.simpan');
        Route::get('/hasil-kuis', [HasilKuisController::class, 'index'])->name('hasil-kuis.index');
    });

    // Detail Hasil Kuis (Bisa dilihat user pemilik atau admin)
    Route::get('/hasil-kuis/{id}', [HasilKuisController::class, 'show'])->name('hasil-kuis.show');

    // Forum Diskusi
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/buat', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{id}/balas', [ForumController::class, 'balas'])->name('forum.balas');

    // Area Admin (Khusus Role Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Artikel
        Route::get('/artikel', [AdminArtikelController::class, 'index'])->name('artikel.index');
        Route::get('/artikel/buat', [AdminArtikelController::class, 'create'])->name('artikel.create');
        Route::post('/artikel', [AdminArtikelController::class, 'store'])->name('artikel.store');
        Route::get('/artikel/{id}/edit', [AdminArtikelController::class, 'edit'])->name('artikel.edit');
        Route::put('/artikel/{id}', [AdminArtikelController::class, 'update'])->name('artikel.update');
        Route::delete('/artikel/{id}', [AdminArtikelController::class, 'destroy'])->name('artikel.destroy');

        // Kelola Kuis
        Route::get('/kuis', [AdminKuisController::class, 'index'])->name('kuis.index');
        Route::get('/kuis/buat', [AdminKuisController::class, 'create'])->name('kuis.create');
        Route::post('/kuis', [AdminKuisController::class, 'store'])->name('kuis.store');
        Route::get('/kuis/{id}', [AdminKuisController::class, 'show'])->name('kuis.show');
        Route::get('/kuis/{id}/edit', [AdminKuisController::class, 'edit'])->name('kuis.edit');
        Route::put('/kuis/{id}', [AdminKuisController::class, 'update'])->name('kuis.update');
        Route::delete('/kuis/{id}', [AdminKuisController::class, 'destroy'])->name('kuis.destroy');
        Route::get('/kuis/{id}/hasil', [AdminKuisController::class, 'hasil'])->name('kuis.hasil');

        // Pertanyaan & Opsi Kuis
        Route::get('/kuis/{id}/pertanyaan/tambah', [AdminKuisController::class, 'tambahPertanyaanForm'])->name('kuis.pertanyaan.create');
        Route::post('/kuis/{id}/pertanyaan', [AdminKuisController::class, 'tambahPertanyaan'])->name('kuis.pertanyaan.store');
        Route::get('/kuis/{id}/pertanyaan/{pertanyaan_id}/edit', [AdminKuisController::class, 'ubahPertanyaanForm'])->name('kuis.pertanyaan.edit');
        Route::put('/kuis/{id}/pertanyaan/{pertanyaan_id}', [AdminKuisController::class, 'ubahPertanyaan'])->name('kuis.pertanyaan.update');
        Route::delete('/kuis/{id}/pertanyaan/{pertanyaan_id}', [AdminKuisController::class, 'hapusPertanyaan'])->name('kuis.pertanyaan.destroy');

        // Moderasi Forum
        Route::get('/forum', [AdminForumController::class, 'index'])->name('forum.index');
        Route::get('/forum/{id}', [AdminForumController::class, 'show'])->name('forum.show');
        Route::delete('/forum/{id}', [AdminForumController::class, 'hapusPostingan'])->name('forum.destroy');
        Route::delete('/forum/balasan/{id}', [AdminForumController::class, 'hapusBalasan'])->name('forum.balasan.destroy');

        // Pengelolaan Pengguna
        Route::get('/pengguna', [AdminPenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/{id}', [AdminPenggunaController::class, 'show'])->name('pengguna.show');
    });
});
