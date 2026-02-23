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
        Schema::create('tb_usulan_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->uuid('id_usulan');
            $table->string('aksi'); // e.g., 'PERUBAHAN_STATUS', 'VALIDASI_BERKAS'
            $table->string('status_usulan')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_usulan')->references('id_usulan')->on('tb_usulan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usulan_log');
    }
};
