<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events') && !Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id('message_id'); 
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('user_id');
                $table->text('message');
                $table->timestamps();

                // Foreign keys
                $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
    }
}