<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('report_number')->after('report_id')->nullable();
        });

        // Set report_number untuk data yang sudah ada
        DB::statement('SET @row_number = 0;');
        DB::statement('
            UPDATE reports 
            SET report_number = (@row_number:=@row_number + 1) 
            ORDER BY report_datetime ASC
        ');

        // Setelah set semua data, buat kolom menjadi NOT NULL
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('report_number')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('report_number');
        });
    }
};