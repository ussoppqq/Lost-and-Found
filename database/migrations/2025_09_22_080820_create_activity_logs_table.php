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
        Schema::create('claims', function (Blueprint $table) {
    $table->uuid('claim_id')->primary();
    $table->uuid('company_id');
    $table->uuid('user_id');
    $table->uuid('item_id');
    $table->enum('claim_status', ['PENDING','APPROVED','REJECTED','RELEASED']);
    $table->dateTime('pickup_schedule')->nullable();
    $table->timestamps();

    $table->foreign('company_id')->references('company_id')->on('companies');
    $table->foreign('user_id')->references('user_id')->on('users');
    $table->foreign('item_id')->references('item_id')->on('items');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
