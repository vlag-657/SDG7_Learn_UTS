<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (MVC Blade Architecture)
|--------------------------------------------------------------------------
| Seluruh rute antarmuka web dipisahkan modular per entitas sistem:
| 1. routes/auth.php    -> Autentikasi Pengguna & Profil Akun
| 2. routes/artikel.php -> Entitas Artikel Edukasi Energi Bersih
| 3. routes/kuis.php    -> Entitas Kuis & Riwayat Hasil Kuis
| 4. routes/forum.php   -> Entitas Forum Diskusi & Balasan Komentar
| 5. routes/admin.php   -> Entitas & Panel Pengelolaan Admin
*/

// Beranda Publik (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Memuat Pemetaan Rute Modular per Entitas
require __DIR__.'/auth.php';
require __DIR__.'/artikel.php';
require __DIR__.'/kuis.php';
require __DIR__.'/forum.php';
require __DIR__.'/call_center.php';
require __DIR__.'/admin.php';
