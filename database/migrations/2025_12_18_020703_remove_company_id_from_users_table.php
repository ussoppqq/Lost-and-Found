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
        Schema::table('users', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $databaseName = env('DB_DATABASE');
            $foreignKeyExists = \DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'users'
                AND CONSTRAINT_NAME = 'users_company_id_foreign'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ", [$databaseName]);

            if (!empty($foreignKeyExists)) {
                \DB::statement('ALTER TABLE users DROP FOREIGN KEY users_company_id_foreign');
            }

            // Drop company_id column if exists
            if (Schema::hasColumn('users', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back company_id column
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->uuid('company_id')->nullable()->after('user_id');
                $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
            }
        });
    }
};
