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
        Schema::create('item_photos', function (Blueprint $table) {
    $table->uuid('photo_id')->primary();
    $table->uuid('company_id');
    $table->uuid('item_id');
    $table->string('photo_url');
    $table->string('alt_text')->nullable();
    $table->integer('display_order')->default(0);
    $table->timestamps();

    $table->foreign('company_id')->references('company_id')->on('companies');
    $table->foreign('item_id')->references('item_id')->on('items');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_photos');
    }
};
