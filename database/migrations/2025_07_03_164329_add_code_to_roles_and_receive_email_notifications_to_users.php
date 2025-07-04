<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar 'code' a roles
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'code')) {
                $table->string('code', 50)->nullable()->after('name');
            }
        });

        // Agregar 'receive_email_notifications' a users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'receive_email_notifications')) {
                $table->boolean('receive_email_notifications')->default(true)->after('email');
            }
        });
    }

    public function down(): void
    {
        // Eliminar 'code' de roles
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'code')) {
                $table->dropColumn('code');
            }
        });

        // Eliminar 'receive_email_notifications' de users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'receive_email_notifications')) {
                $table->dropColumn('receive_email_notifications');
            }
        });
    }
};
