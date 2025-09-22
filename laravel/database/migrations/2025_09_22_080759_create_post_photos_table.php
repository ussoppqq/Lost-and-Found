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
        Schema::create('items', function (Blueprint $table) {
    $table->uuid('item_id')->primary();
    $table->uuid('company_id');
    $table->uuid('post_id')->nullable();
    $table->uuid('category_id')->nullable();
    $table->string('item_name');
    $table->string('brand')->nullable();
    $table->string('color')->nullable();
    $table->text('item_description')->nullable();
    $table->string('storage')->nullable();
    $table->enum('item_status', ['REGISTERED','STORED','CLAIMED','DISPOSED','RETURNED']);
    $table->dateTime('retention_until')->nullable();
    $table->enum('sensitivity_level', ['NORMAL','RESTRICTED']);
    $table->timestamps();

    $table->foreign('company_id')->references('company_id')->on('companies');
    $table->foreign('post_id')->references('post_id')->on('posts');
    $table->foreign('category_id')->references('category_id')->on('categories');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
