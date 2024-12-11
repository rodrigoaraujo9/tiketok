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
                $table->id();
                $table->foreignId('poll_id')->constrained('polls')->onDelete('cascade');
                $table->string('option');
                $table->unsignedInteger('votes')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('poll_options');
    }
}

