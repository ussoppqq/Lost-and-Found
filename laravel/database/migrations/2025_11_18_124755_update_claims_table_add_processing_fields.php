<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            // Update enum - hapus APPROVED, hanya 3 status
            DB::statement("ALTER TABLE claims MODIFY COLUMN claim_status ENUM('PENDING', 'RELEASED', 'REJECTED') DEFAULT 'PENDING'");
            
            // Tracking siapa yang process
            $table->uuid('processed_by')->nullable()->after('claim_status');
            $table->dateTime('processed_at')->nullable()->after('processed_by');
            
            // Rejection reason - WAJIB jika status REJECTED
            $table->text('rejection_reason')->nullable()->after('processed_at');
            
            // Foreign key
            $table->foreign('processed_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['processed_by', 'processed_at', 'rejection_reason']);
            
            // Revert enum back if needed
            DB::statement("ALTER TABLE claims MODIFY COLUMN claim_status ENUM('PENDING','APPROVED','REJECTED','RELEASED')");
        });
    }
};