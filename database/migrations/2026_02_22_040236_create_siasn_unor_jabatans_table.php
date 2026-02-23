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
        Schema::create('siasn_unor_jabatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usulan')->nullable()->comment('Relasi ke tabel usulan jika ada');
            $table->string('pns_id')->nullable()->comment('ID PNS di SIASN');
            $table->string('nip')->nullable();
            
            // Payload createUnorJabatan
            $table->string('unor_id')->nullable();
            $table->string('instansi_id')->nullable();
            $table->string('eselon_id')->nullable();
            $table->string('jabatan_fungsional_id')->nullable();
            $table->string('jabatan_fungsional_umum_id')->nullable();
            $table->string('satuan_kerja_id')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->date('tmt_pelantikan')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('id_riwayat_jabatan_siasn')->nullable()->comment('ID Riwayat kembalian dari SIASN setelah save');
            
            $table->boolean('is_sync')->default(false)->comment('Penanda apakah sudah berhasil masuk SIASN');
            $table->json('sync_response')->nullable()->comment('Response error / sukses dari SIASN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siasn_unor_jabatans');
    }
};
