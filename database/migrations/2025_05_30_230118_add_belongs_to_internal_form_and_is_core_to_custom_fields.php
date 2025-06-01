<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_fields', 'is_public')) {
                $table->boolean('is_public')
                      ->default(false)
                      ->after('custom_id');
            }
            
            if (!Schema::hasColumn('custom_fields', 'is_core')) {
                $table->boolean('is_core')
                      ->default(true)
                      ->after('is_public');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            if (Schema::hasColumn('custom_fields', 'is_public')) {
                $table->dropColumn('is_public');
            }
            
            if (Schema::hasColumn('custom_fields', 'is_core')) {
                $table->dropColumn('is_core');
            }
        });
    }
};