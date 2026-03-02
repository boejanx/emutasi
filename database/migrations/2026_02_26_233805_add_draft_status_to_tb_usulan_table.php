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
            $table->string('no_surat')->nullable()->change();
            $table->date('tanggal_surat')->nullable()->change();
            $table->string('perihal')->nullable()->change();
            
            // Mengubah tipe kolom status menjadi string jika memungkinkan, 
            // Namun karena ini tinyInteger (0, 1, 2, 3), lebih aman kita biarkan type nya 
            // Kita gunakan status '99' untuk draft misal (karena TinyInteger)
            // Jadi baris sebelumnya ditiadakan agar tidak error DBAL 'Unknown column type'.

            $table->timestamp('submitted_at')->nullable()->after('status');

            $table->index(['id_user', 'status'], 'idx_user_status');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usulan', function (Blueprint $table) {
            $table->dropIndex('idx_user_status');
            $table->dropIndex('idx_status');
            
            $table->dropColumn('submitted_at');

            // Rollback nullable jika bisa, tidak bisa secara eksak karena butuh definisi awal
            $table->string('no_surat')->nullable(false)->change();
            $table->date('tanggal_surat')->nullable(false)->change();
            $table->string('perihal')->nullable(false)->change();
        });
    }
};
