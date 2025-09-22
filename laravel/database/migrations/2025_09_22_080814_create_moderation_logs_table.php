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
        Schema::create('reports', function (Blueprint $table) {
    $table->uuid('report_id')->primary();
    $table->uuid('company_id');
    $table->uuid('user_id')->nullable();
    $table->uuid('item_id')->nullable();
    $table->enum('report_type', ['LOST','FOUND']);
    $table->text('report_description');
    $table->dateTime('report_datetime');
    $table->string('report_location');
    $table->enum('report_status', ['OPEN','STORED','MATCHED','CLOSED']);
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
        Schema::dropIfExists('reports');
    }
};
