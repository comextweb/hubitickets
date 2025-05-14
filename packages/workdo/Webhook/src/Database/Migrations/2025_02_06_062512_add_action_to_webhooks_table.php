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
        if (Schema::hasTable('webhooks') && !Schema::hasColumn('webhooks', 'action')) {
            Schema::table('webhooks', function (Blueprint $table) {
                $table->integer('action')->after('method')->nullable();
            });
        }

        if (Schema::hasTable('webhooks') && Schema::hasColumn('webhooks', 'module')) {
            Schema::table('webhooks', function (Blueprint $table) {
                $table->dropColumn('module');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webhooks', function (Blueprint $table) {
            //
        });
    }
};
