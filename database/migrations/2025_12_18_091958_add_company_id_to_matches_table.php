<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->uuid('company_id')->nullable()->after('match_id');
            
            // Jika ingin menambahkan foreign key constraint
            $table->foreign('company_id')
                  ->references('company_id')
                  ->on('companies')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};