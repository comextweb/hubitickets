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
        if (Schema::hasTable('notification_templates') && Schema::hasColumn('notification_templates', 'name')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->dropColumn(['name']);
                $table->dropColumn(['slug']);
            });
        }

        if (Schema::hasTable('notification_templates') && !Schema::hasColumn('notification_templates', 'module')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('module')->nullable()->after(column: 'id');
            });
        }

        if (Schema::hasTable('notification_templates') && !Schema::hasColumn('notification_templates', 'type')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('type')->nullable()->after(column: 'module');
            });
        }

        if (Schema::hasTable('notification_templates') && !Schema::hasColumn('notification_templates', 'action')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('action')->nullable()->after(column: 'type');
            });
        }

        if (Schema::hasTable('notification_templates') && !Schema::hasColumn('notification_templates', 'from')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('from')->nullable()->after(column: 'action');
            });
        }

        if (Schema::hasTable('notification_templates') && !Schema::hasColumn('notification_templates', 'created_by')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->integer('created_by')->nullable()->after(column: 'from');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            //
        });
    }
};
