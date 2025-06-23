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
        // Para users
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique'); // Elimina unicidad simple
            $table->unique(['company_id', 'email']); // Agrega unicidad compuesta
        });

        // Para roles
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique'); // Elimina unicidad simple
            $table->unique(['company_id', 'name']); // Agrega unicidad compuesta
        });

        // Para settings
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['name', 'created_by']);
            $table->unique(['company_id','created_by', 'name']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'email']);
            $table->unique('email');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->unique('name');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['company_id','created_by', 'name']);
            $table->unique(['name', 'created_by']);
        });
    }
};
