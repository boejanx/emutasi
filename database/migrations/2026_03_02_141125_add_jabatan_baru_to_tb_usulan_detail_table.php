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
            $table->string('jenis_jabatan_baru')->nullable();
            $table->string('jabatan_baru_id')->nullable();
            $table->string('jabatan_baru_nama')->nullable();
            $table->string('sub_unor_id')->nullable();
            $table->string('sub_unor_nama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_jabatan_baru',
                'jabatan_baru_id',
                'jabatan_baru_nama',
                'sub_unor_id',
                'sub_unor_nama'
            ]);
        });
    }
};
