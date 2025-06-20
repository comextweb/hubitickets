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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('ruc');
            $table->string('phone');
            $table->boolean('is_active')->default(1);
            $table->string('created_by');
            $table->string('slug')->unique(); // Identificador único para URLs
            $table->string('subdomain')->unique(); // Subdominio único para la empresa
            $table->json('config')->nullable(); // Configuraciones específicas
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
