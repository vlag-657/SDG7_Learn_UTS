<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('call_center', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admin')->nullOnDelete();
            $table->string('nama');
            $table->string('email');
            $table->string('nomor_telepon')->nullable();
            $table->string('kategori');
            $table->string('prioritas')->default('sedang'); // rendah, sedang, tinggi, darurat
            $table->string('subjek');
            $table->text('pesan');
            $table->string('status')->default('menunggu'); // menunggu, diproses, selesai
            $table->text('tanggapan')->nullable();
            $table->dateTime('ditanggapi_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_center');
    }
};
