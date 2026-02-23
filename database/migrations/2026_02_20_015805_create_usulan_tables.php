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
        Schema::create('tb_usulan', function (Blueprint $table) {
            $table->uuid('id_usulan')->primary();
            $table->string('no_surat');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('status')->default(1)->comment('0=usulan ditolak, 1=surat diterima, 2=surat terkirim, 3=usulan diproses');
            $table->tinyInteger('disposisi')->default(0)->comment('0=belum disposisi, 1=disposisi ke kabid, 2=disposisi ke pelaksana');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tb_usulan_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignUuid('id_usulan')->constrained('tb_usulan', 'id_usulan')->onDelete('cascade');
            $table->string('nip');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('lokasi_awal');
            $table->string('lokasi_tujuan');
            $table->tinyInteger('status')->default(1)->comment('0=usulan ditolak, 1=berkas usulan lengkap, 2=berkas usulan revisi, 3=berkas usulan tidak lengkap');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tb_usulan_berkas', function (Blueprint $table) {
            $table->id('id_berkas');
            $table->unsignedBigInteger('id_usulan_detail');
            $table->foreign('id_usulan_detail')->references('id_detail')->on('tb_usulan_detail')->onDelete('cascade');
            $table->string('id_dokumen')->nullable();
            $table->string('path_dokumen');
            $table->tinyInteger('status')->default(0)->comment('0=revisi, 1=sesuai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usulan_berkas');
        Schema::dropIfExists('tb_usulan_detail');
        Schema::dropIfExists('tb_usulan');
    }
};
