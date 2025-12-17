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
        // Cek dan drop foreign key jika ada menggunakan raw SQL
        $databaseName = env('DB_DATABASE');
        $foreignKeyExists = \DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = 'locations'
            AND CONSTRAINT_NAME = 'locations_area_id_foreign'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$databaseName]);

        if (!empty($foreignKeyExists)) {
            \DB::statement('ALTER TABLE locations DROP FOREIGN KEY locations_area_id_foreign');
        }

        Schema::table('locations', function (Blueprint $table) {
            // Drop kolom area_id jika ada
            if (Schema::hasColumn('locations', 'area_id')) {
                $table->dropColumn('area_id');
            }

            // Tambah kolom area sebagai string jika belum ada
            if (!Schema::hasColumn('locations', 'area')) {
                $table->string('area')->after('location_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Hapus kolom area
            $table->dropColumn('area');
            // Tambah kembali area_id
            $table->uuid('area_id')->nullable()->after('location_id');
            $table->foreign('area_id')->references('area_id')->on('areas');
        });
    }
};
