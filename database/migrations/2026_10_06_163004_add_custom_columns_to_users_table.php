<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom-kolom custom ke tabel users yang sudah ada.
     * (users dibuat oleh migration bawaan Laravel, kita extend di sini)
     */
    public function up(): void
    {
        // 1. Buat tabel admin sesuai entitas Admin pada ERD (ID, Nama, Password)
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Modifikasi tabel users sesuai entitas User pada ERD (ID, Nama, Pekerjaan, Usia, Password, ID_Forum)
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->string('pekerjaan')->nullable()->after('email');
            $table->unsignedTinyInteger('usia')->nullable()->after('pekerjaan');
            $table->unsignedBigInteger('id_forum')->nullable()->after('usia'); // Atribut ID_Forum pada ERD
            $table->enum('role', ['user', 'admin'])->default('user')->after('id_forum');

            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn(['nama', 'pekerjaan', 'usia', 'id_forum', 'role']);
        });

        Schema::dropIfExists('admin');
    }
};
