<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->unsignedBigInteger('poll_id');
            $table->text('option_text');
            $table->timestamps();

            $table->foreign('poll_id')->references('poll_id')->on('polls')->cascadeOnDelete();
        });
    }
};
