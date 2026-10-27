<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel quiz sesuai ERD: ID, isi, Poin + relasi Admin (admin_id)
        Schema::create('quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
            $table->string('judul');
            $table->text('isi')->nullable(); // Atribut isi pada ERD
            $table->unsignedInteger('poin')->default(10); // Atribut Poin pada ERD
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 2. Tabel kuis untuk kompatibilitas penuh kode yang ada
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
            $table->string('judul');
            $table->text('isi')->nullable();
            $table->unsignedInteger('poin')->default(10);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->nullable()->constrained('quiz')->cascadeOnDelete();
            $table->foreignId('kuis_id')->nullable()->constrained('kuis')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->text('teks');
            $table->unsignedInteger('poin')->default(1);
            $table->text('penjelasan')->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'urutan']);
            $table->index(['kuis_id', 'urutan']);
        });

        Schema::create('opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->cascadeOnDelete();
            $table->text('teks');
            $table->boolean('benar')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi');
        Schema::dropIfExists('pertanyaan');
        Schema::dropIfExists('kuis');
        Schema::dropIfExists('quiz');
    }
};
