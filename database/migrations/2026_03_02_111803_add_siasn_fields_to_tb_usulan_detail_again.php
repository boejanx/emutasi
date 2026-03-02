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
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('pangkat_akhir')->nullable();
            $table->string('tmt_gol_akhir')->nullable();
            $table->string('pendidikan_terakhir_nama')->nullable();
            $table->string('jabatan_nama')->nullable();
            $table->string('unor_nama')->nullable();
            $table->string('unor_induk_nama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'pangkat_akhir',
                'tmt_gol_akhir',
                'pendidikan_terakhir_nama',
                'jabatan_nama',
                'unor_nama',
                'unor_induk_nama',
            ]);
        });
    }
};
