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
        Schema::create('categories', function (Blueprint $table) {
    $table->uuid('category_id')->primary();
    $table->uuid('company_id');
    $table->string('category_name');
    $table->string('subcategory_name')->nullable();
    $table->integer('retention_days');
    $table->boolean('is_restricted')->default(false);

    $table->foreign('company_id')->references('company_id')->on('companies');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
