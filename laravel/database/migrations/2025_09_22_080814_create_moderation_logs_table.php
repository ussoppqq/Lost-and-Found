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

            // Relations
            $table->uuid('company_id')->index();
            $table->uuid('user_id')->nullable()->index();
            $table->uuid('item_id')->nullable()->index();
            $table->uuid('category_id')->nullable()->index();

            // Report details
            $table->enum('report_type', ['LOST','FOUND']);
            $table->string('item_name')->nullable(); // untuk sementara sebelum item dibuat
            $table->text('report_description')->nullable();
            $table->dateTime('report_datetime');
            $table->string('report_location');
            $table->enum('report_status', ['OPEN','STORED','MATCHED','CLOSED'])->default('OPEN');
            $table->string('photo_url')->nullable();

            // Reporter (kalau tidak ada user login)
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone')->nullable();
            $table->string('reporter_email')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')
                  ->references('company_id')->on('companies')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');

            $table->foreign('item_id')
                  ->references('item_id')->on('items')
                  ->onDelete('set null');

            $table->foreign('category_id')
                  ->references('category_id')->on('categories')
                  ->onDelete('set null');
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
