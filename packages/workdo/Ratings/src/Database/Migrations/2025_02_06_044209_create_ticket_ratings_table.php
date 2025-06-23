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
        if (!Schema::hasTable('ticket_ratings')) {
            Schema::create('ticket_ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->String('customer');
                $table->unsignedBigInteger('user_id')->default(0);
                $table->date('rating_date')->nullable();
                $table->float('rating')->default('0.00');
                $table->longText('description')->nullable();
                $table->unsignedBigInteger('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_ratings');
    }
};
