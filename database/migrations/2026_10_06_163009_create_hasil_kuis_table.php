<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mewakili relasi ERD: User -- [Mengerjakan dan Menampilkan skor] -- Quiz
        Schema::create('hasil_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->nullable()->constrained('quiz')->cascadeOnDelete();
            $table->foreignId('kuis_id')->nullable()->constrained('kuis')->cascadeOnDelete();
            $table->string('kuis_judul');           // snapshot judul saat pengerjaan
            $table->unsignedInteger('skor')->default(0); // Atribut 'skor' sesuai relasi ERD
            $table->unsignedInteger('skor_maks')->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->unsignedInteger('jumlah_benar')->default(0);
            $table->unsignedInteger('jumlah_pertanyaan')->default(0);
            $table->json('tinjauan');               // snapshot per-pertanyaan
            $table->timestamps();

            $table->index(['user_id', 'quiz_id']);
            $table->index(['user_id', 'kuis_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuis');
    }
};
