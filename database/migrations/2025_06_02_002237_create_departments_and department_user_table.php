<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create table for storing departments
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });

        // Create table for associating departments to users (Many To Many)
        Schema::create('department_user', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('user_id');
            
            // Llaves foráneas
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            // Llave primaria compuesta
            $table->primary(['department_id', 'user_id']);
        });

        // Tabla para managers de departamento (Relación 1 a muchos)
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('manager_id')
                  ->nullable()
                  ->constrained('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
        
        Schema::dropIfExists('department_user');
        Schema::dropIfExists('departments');
    }
};