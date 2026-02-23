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
            $table->string('siasn_id')->nullable()->comment('pnsId dari SIASN');
            $table->string('unor_id_tujuan')->nullable()->comment('ID UNOR Tujuan di SIASN');
            $table->string('nama_unor_tujuan')->nullable()->comment('Nama UNOR Tujuan di SIASN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->dropColumn(['siasn_id', 'unor_id_tujuan', 'nama_unor_tujuan']);
        });
    }
};
