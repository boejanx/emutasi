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
        Schema::create('tb_usulan_pesan', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->string('id_usulan', 36);
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->text('pesan');
            $table->timestamps();

            $table->foreign('id_usulan')->references('id_usulan')->on('tb_usulan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usulan_pesan');
    }
};
