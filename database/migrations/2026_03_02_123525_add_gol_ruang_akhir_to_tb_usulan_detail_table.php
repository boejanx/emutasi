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
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->string('gol_ruang_akhir')->nullable()->after('pangkat_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->dropColumn('gol_ruang_akhir');
        });
    }
};
