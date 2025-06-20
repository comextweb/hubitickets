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
        // users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // tickets
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // roles
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // custom_fields
        Schema::table('custom_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_fields', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // departments
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // faqs
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // knowledge
        Schema::table('knowledge', function (Blueprint $table) {
            if (!Schema::hasColumn('knowledge', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // knowledge_base_category
        Schema::table('knowledge_base_category', function (Blueprint $table) {
            if (!Schema::hasColumn('knowledge_base_category', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // priorities
        Schema::table('priorities', function (Blueprint $table) {
            if (!Schema::hasColumn('priorities', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // tags
        Schema::table('tags', function (Blueprint $table) {
            if (!Schema::hasColumn('tags', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // ticket_ratings
        Schema::table('ticket_ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_ratings', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // categories
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });

        // settings
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'company_id')) {
                $table->foreignId('company_id')->after('id')
                    ->nullable()
                    ->constrained('companies')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        });


        // Agrega más tablas según necesites
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('knowledge', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('knowledge_base_category', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('priorities', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('ticket_ratings', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
        // Repite para otras tablas
    }
};

