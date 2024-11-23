<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id('venue_id'); // Primary key
            $table->string('name')->unique(); // Venue name
            $table->string('location'); // Venue location
            $table->integer('max_capacity')->unsigned(); // Maximum capacity of the venue
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
