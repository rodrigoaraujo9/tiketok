<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->unsignedBigInteger('event_id');
            $table->enum('type', ['regular', 'vip', 'student']);
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->foreign('event_id')->references('event_id')->on('events')->cascadeOnDelete();
        });
    }
};
