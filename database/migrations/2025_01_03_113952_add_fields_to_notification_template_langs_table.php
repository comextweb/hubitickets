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
        if (Schema::hasTable('notification_template_langs') && !Schema::hasColumn('notification_template_langs', 'module')) {
            Schema::table('notification_template_langs', function (Blueprint $table) {
                $table->string('module')->nullable()->after(column: 'lang');
            });
        }

        if (Schema::hasTable('notification_template_langs') && !Schema::hasColumn('notification_template_langs', 'subject')) {
            Schema::table('notification_template_langs', function (Blueprint $table) {
                $table->string('subject')->nullable()->after(column: 'content');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_template_langs', function (Blueprint $table) {
            //
        });
    }
};
