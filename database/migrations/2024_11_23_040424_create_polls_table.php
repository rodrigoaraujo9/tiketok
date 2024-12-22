<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('polls')) {
            Schema::create('polls', function (Blueprint $table) {
                $table->id('poll_id');
                $table->foreignId('event_id')
                    ->constrained('events', 'event_id')
                    ->onDelete('cascade');
                    
                $table->foreignId('comment_id')
                    ->nullable()
                    ->constrained('comments', 'comment_id')
                    ->onDelete('cascade');
                    
                $table->string('question');
                $table->timestamp('end_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
