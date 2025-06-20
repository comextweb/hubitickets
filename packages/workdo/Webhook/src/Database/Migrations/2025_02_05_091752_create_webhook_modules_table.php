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
        if (!Schema::hasTable('webhook_modules')) {
            Schema::create('webhook_modules', function (Blueprint $table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('submodule')->nullable();
                $table->string('type')->default('admin');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_modules');
    }
};
