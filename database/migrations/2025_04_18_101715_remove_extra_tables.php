<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('floating_chat_users');
        Schema::dropIfExists('floating_chat_messages');
    }


    public function down(): void
    {
        Schema::create(
            'messages',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('from');
                $table->bigInteger('to');
                $table->text('message');
                $table->tinyInteger('is_read');
                $table->timestamps();
            }
        );


        Schema::create(
            'floating_chat_users',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('email');
                $table->tinyInteger('is_end')->default(0);
                $table->timestamps();
            }
        );

        Schema::create(
            'floating_chat_messages',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('from');
                $table->bigInteger('to');
                $table->text('message');
                $table->tinyInteger('is_read');
                $table->timestamps();
            }
        );
    }
};
