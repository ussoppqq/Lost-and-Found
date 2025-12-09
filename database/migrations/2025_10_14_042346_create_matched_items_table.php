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
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid('match_id')->primary();
            
            // Relasi ke reports
            $table->uuid('lost_report_id')->index();
            $table->uuid('found_report_id')->index();
            
            // Match metadata
            $table->enum('match_status', ['PENDING','CONFIRMED','REJECTED'])->default('PENDING');
            $table->decimal('confidence_score', 5, 2)->nullable(); // 0.00 - 100.00
            $table->text('match_notes')->nullable();
            
            // Who and when
            $table->uuid('matched_by')->nullable(); // admin/user yang match
            $table->dateTime('matched_at');
            $table->dateTime('confirmed_at')->nullable();
            $table->uuid('confirmed_by')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('lost_report_id')
                  ->references('report_id')->on('reports')
                  ->onDelete('cascade');

            $table->foreign('found_report_id')
                  ->references('report_id')->on('reports')
                  ->onDelete('cascade');

            $table->foreign('matched_by')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');

            $table->foreign('confirmed_by')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');

            // Unique constraint: satu pasangan LOST-FOUND hanya bisa match sekali
            $table->unique(['lost_report_id', 'found_report_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};