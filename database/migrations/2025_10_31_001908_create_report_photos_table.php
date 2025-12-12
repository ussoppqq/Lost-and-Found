<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_photos', function (Blueprint $table) {
            $table->uuid('photo_id')->primary();
            $table->uuid('report_id');
            $table->string('photo_url');
            $table->boolean('is_primary')->default(false);
            $table->unsignedTinyInteger('photo_order')->default(0);
            $table->timestamps();

            $table->foreign('report_id')
                  ->references('report_id')
                  ->on('reports')
                  ->onDelete('cascade');
            
            $table->index('report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_photos');
    }
};