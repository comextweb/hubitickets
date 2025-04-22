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
        if (Schema::hasTable('model_has_roles')) {
            Schema::dropIfExists('model_has_roles');
        }

        if (Schema::hasTable('role_has_permissions')) {
            Schema::dropIfExists('role_has_permissions');
        }

        if (Schema::hasTable('model_has_permissions')) {
            Schema::dropIfExists('model_has_permissions');
        }

        if (Schema::hasTable('permissions')) {
            Schema::dropIfExists('permissions');
        }

        if (Schema::hasTable('roles')) {
            Schema::dropIfExists('roles');
        }

        if (Schema::hasTable('email_templates')) {
            Schema::dropIfExists('email_templates');
        }

        if (Schema::hasTable('email_template_langs')) {
            Schema::dropIfExists('email_template_langs');
        }

        if (Schema::hasTable('user_email_templates')) {
            Schema::dropIfExists('user_email_templates');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
