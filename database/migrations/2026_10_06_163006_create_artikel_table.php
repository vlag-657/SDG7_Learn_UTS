<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel artikel sesuai ERD: ID, ISI, Tanggal, Sumber + relasi Admin (admin_id)
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

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
