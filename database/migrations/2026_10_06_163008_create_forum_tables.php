<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel forum sesuai ERD: ID, Isi, Tanggal + relasi User (Berdiskusi) & Admin (Membuat/Mengelola)
        Schema::create('forum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
            $table->string('judul');
            $table->longText('isi'); // Atribut Isi pada ERD
            $table->dateTime('tanggal')->nullable(); // Atribut Tanggal pada ERD
            $table->timestamps();
        });

        // 2. Tabel postingan untuk kompatibilitas penuh kode yang ada
        Schema::create('postingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
            $table->string('judul');
            $table->longText('isi');
            $table->dateTime('tanggal')->nullable();
            $table->timestamps();
        });

        Schema::create('balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->nullable()->constrained('forum')->cascadeOnDelete();
            $table->foreignId('postingan_id')->nullable()->constrained('postingan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balasan');
        Schema::dropIfExists('postingan');
        Schema::dropIfExists('forum');
    }
};
