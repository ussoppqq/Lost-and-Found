<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            // Relasi ke companies (nullable karena user biasa mungkin tidak punya company)
            $table->uuid('company_id')->nullable();

            // Relasi ke roles (wajib diisi)
            $table->uuid('role_id');

            $table->string('full_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone_number')->unique();
            $table->string('password')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')
                  ->references('company_id')
                  ->on('companies')
                  ->onDelete('set null');

            $table->foreign('role_id')
                  ->references('role_id')
                  ->on('roles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
