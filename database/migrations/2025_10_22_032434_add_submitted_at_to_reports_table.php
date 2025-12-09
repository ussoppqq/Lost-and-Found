<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom submitted_at ke tabel reports
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Cek dulu kalau kolom belum ada (biar aman kalau migrate dua kali)
            if (!Schema::hasColumn('reports', 'submitted_at')) {
                $table->timestamp('submitted_at')
                      ->nullable() // boleh kosong
                      ->after('report_status') // posisinya setelah kolom report_status
                      ->index(); // buat index biar pencarian lebih cepat
            }
        });
    }

    /**
     * Hapus kolom submitted_at kalau di-rollback
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};