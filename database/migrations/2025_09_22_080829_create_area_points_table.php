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
        Schema::create('item_histories', function (Blueprint $table) {
    $table->uuid('history_id')->primary();
    $table->uuid('company_id');
    $table->uuid('item_id');
    $table->enum('event_type', [
        'CREATED','LOST_REPORT_LINKED','FOUND_REPORT_LINKED','STATUS_CHANGED',
        'CLAIM_CREATED','CLAIM_APPROVED','CLAIM_REJECTED','RELEASED','MOVED','UPDATED'
    ]);
    $table->string('item_status');
    $table->uuid('actor_id')->nullable();
    $table->uuid('report_id')->nullable();
    $table->uuid('claim_id')->nullable();
    $table->uuid('post_id')->nullable();
    $table->text('notes')->nullable();
    $table->dateTime('occurred_at');
    $table->timestamps();

    $table->foreign('company_id')->references('company_id')->on('companies');
    $table->foreign('item_id')->references('item_id')->on('items');
    $table->foreign('actor_id')->references('user_id')->on('users');
    $table->foreign('claim_id')->references('claim_id')->on('claims');
    $table->foreign('post_id')->references('post_id')->on('posts');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_histories');
    }
};
