<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('item_id');
            $table->string('color')->nullable()->after('brand');
            $table->text('claim_notes')->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['brand', 'color', 'claim_notes']);
        });
    }
};