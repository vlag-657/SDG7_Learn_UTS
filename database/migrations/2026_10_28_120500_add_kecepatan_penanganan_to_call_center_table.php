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
        Schema::table('call_center', function (Blueprint $table) {
            $table->string('kecepatan_penanganan')->nullable()->after('ditanggapi_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_center', function (Blueprint $table) {
            $table->dropColumn('kecepatan_penanganan');
        });
    }
};
