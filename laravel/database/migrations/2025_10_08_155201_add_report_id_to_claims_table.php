<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->uuid('report_id')->nullable()->after('item_id'); 
            $table->foreign('report_id')->references('report_id')->on('reports')->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['report_id']); 
            $table->dropColumn('report_id');
        });
    }
};