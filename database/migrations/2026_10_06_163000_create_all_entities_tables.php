<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skema Lengkap Seluruh Entitas SDG7Learn (Sesuai ERD & SKPL UTS)
     */
    public function up(): void
    {
        // 1. Entitas USER
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('pekerjaan')->nullable();
                $table->unsignedTinyInteger('usia')->nullable();
                $table->unsignedBigInteger('id_forum')->nullable();
                $table->enum('role', ['user', 'admin'])->default('user');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 2. Entitas ADMIN
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 3. Entitas ARTIKEL (Admin Mengelola Artikel)
        if (!Schema::hasTable('artikel')) {
            Schema::create('artikel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
                $table->string('judul');
                $table->longText('isi');
                $table->string('sumber');
                $table->dateTime('tanggal')->nullable();
                $table->string('gambar_url')->nullable();
                $table->timestamps();
            });
        }

        // 4. Entitas QUIZ / KUIS (Admin Membuat Quiz)
        if (!Schema::hasTable('quiz')) {
            Schema::create('quiz', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
                $table->string('judul');
                $table->text('isi')->nullable();
                $table->unsignedInteger('poin')->default(10);
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        // 5. Entitas PERTANYAAN (Soal dalam Quiz)
        if (!Schema::hasTable('pertanyaan')) {
            Schema::create('pertanyaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')->constrained('quiz')->cascadeOnDelete();
                $table->unsignedSmallInteger('urutan')->default(1);
                $table->text('teks');
                $table->unsignedInteger('poin')->default(1);
                $table->text('penjelasan')->nullable();
                $table->timestamps();
            });
        }

        // 6. Entitas OPSI (Pilihan Jawaban Soal)
        if (!Schema::hasTable('opsi')) {
            Schema::create('opsi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->cascadeOnDelete();
                $table->text('teks');
                $table->boolean('benar')->default(false);
                $table->timestamps();
            });
        }

        // 7. Entitas FORUM & POSTINGAN (User Berdiskusi di Forum)
        if (!Schema::hasTable('forum')) {
            Schema::create('forum', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
                $table->string('judul');
                $table->longText('isi');
                $table->dateTime('tanggal')->nullable();
                $table->timestamps();
            });
        }

        // 8. Entitas BALASAN (Komentar Diskusi Forum)
        if (!Schema::hasTable('balasan')) {
            Schema::create('balasan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('forum_id')->constrained('forum')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->longText('isi');
                $table->timestamps();
            });
        }

        // 9. Entitas HASIL KUIS (User Mengerjakan & Skor Quiz)
        if (!Schema::hasTable('hasil_kuis')) {
            Schema::create('hasil_kuis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('quiz_id')->constrained('quiz')->cascadeOnDelete();
                $table->string('kuis_judul');
                $table->unsignedInteger('skor')->default(0);
                $table->unsignedInteger('skor_maks')->default(0);
                $table->decimal('persentase', 5, 2)->default(0);
                $table->unsignedInteger('jumlah_benar')->default(0);
                $table->unsignedInteger('jumlah_pertanyaan')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuis');
        Schema::dropIfExists('balasan');
        Schema::dropIfExists('forum');
        Schema::dropIfExists('opsi');
        Schema::dropIfExists('pertanyaan');
        Schema::dropIfExists('quiz');
        Schema::dropIfExists('artikel');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('users');
    }
};
