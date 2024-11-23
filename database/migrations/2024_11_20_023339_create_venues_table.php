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
        Schema::create('venues', function (Blueprint $table) {
            $table->id('venue_id'); // Primary key
            $table->string('name')->unique(); // Unique venue name
            $table->string('location'); // Location of the venue
            $table->unsignedInteger('max_capacity')->nullable(); // Optional max capacity
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
    }
};
