<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (Schema::hasTable('add_ons') && !Schema::hasColumn('add_ons', 'alias_name')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->string('alias_name')->after('name')->nullable();
            });
        }
    }


    public function down(): void
    {
        if (Schema::hasTable('add_ons') && Schema::hasColumn('add_ons', 'alias_name')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->dropColumn('alias_name');
            });
        }
    }
};
