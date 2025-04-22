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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'is_enable_login')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('is_enable_login')->after('parent')->default(1);
            });
        }
    }


    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'is_enable_login')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_enable_login');
            });
        }
    }
};
