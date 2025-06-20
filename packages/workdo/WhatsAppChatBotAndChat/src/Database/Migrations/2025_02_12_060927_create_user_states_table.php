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
        if (!Schema::hasTable('user_states')) {
            Schema::create('user_states', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name')->nullable();
                $table->integer('ticket_id')->nullable();
                $table->string('email')->nullable();
                $table->string('user_mobile')->nullable();
                $table->string('state')->nullable();
                $table->text('subject')->nullable();
                $table->text('description')->nullable();
                $table->integer('category_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_states');
    }
};
