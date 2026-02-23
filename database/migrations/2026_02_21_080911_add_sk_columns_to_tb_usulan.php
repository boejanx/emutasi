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
        Schema::table('tb_usulan', function (Blueprint $table) {
            $table->string('nomor_sk')->nullable()->after('perihal');
            $table->string('path_sk')->nullable()->after('nomor_sk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan', function (Blueprint $table) {
            $table->dropColumn(['nomor_sk', 'path_sk']);
        });
    }
};
