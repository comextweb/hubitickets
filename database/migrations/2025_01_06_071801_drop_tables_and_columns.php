<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (Schema::hasTable('sub_categories')) {
            Schema::drop('sub_categories');
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'subcategory')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('subcategory');
            });
        }

        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'subcategory')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('subcategory');
            });
        }
    }


    public function down(): void
    {
        //
    }
};
