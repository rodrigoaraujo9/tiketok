<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollOptionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('poll_options')) {
            Schema::create('poll_options', function (Blueprint $table) {
                $table->id('option_id');
                $table->foreignId('poll_id')->constrained('polls', 'poll_id')->onDelete('cascade');
                $table->string('option_text'); 
                $table->integer('votes')->default(0);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('poll_options');
    }
}
