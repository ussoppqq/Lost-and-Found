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
        Schema::create('users', function (Blueprint $table) {
    $table->uuid('user_id')->primary();
    $table->uuid('company_id');
    $table->uuid('role_id');
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('phone_number')->nullable();
    $table->string('password')->nullable();
    $table->timestamps();

    $table->foreign('company_id')->references('company_id')->on('companies');
    $table->foreign('role_id')->references('role_id')->on('roles');
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
