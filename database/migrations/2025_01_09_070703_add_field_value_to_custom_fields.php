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
        Schema::table('custom_fields', function (Blueprint $table) {
            if (Schema::hasTable('custom_fields') && !Schema::hasColumn('custom_fields', 'fieldValue')) {
                Schema::table('custom_fields', function (Blueprint $table) {
                    $table->text('fieldValue')->nullable()->after('width');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            //
        });
    }
};
