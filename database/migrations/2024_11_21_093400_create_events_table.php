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
    if (!Schema::hasTable('events')) {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->date('date');
            $table->string('postalCode');
            $table->unsignedInteger('maxEventCapacity');
            $table->string('country');
            $table->enum('visibility', ['public', 'private']);
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
