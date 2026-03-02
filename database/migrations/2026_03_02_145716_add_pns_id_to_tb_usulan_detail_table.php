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
            $table->string('pns_id')->nullable()->after('siasn_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan_detail', function (Blueprint $table) {
            $table->dropColumn('pns_id');
        });
    }
};
