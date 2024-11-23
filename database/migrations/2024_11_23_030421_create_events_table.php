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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id'); // Primary key
            $table->text('description'); // Event description
            $table->timestamp('date')->check('date >= CURRENT_DATE'); // Event date
            $table->string('postal_code', 10)->nullable(); // Optional postal code
            $table->unsignedInteger('max_event_capacity')->nullable(); // Optional max event capacity
            $table->string('country'); // Country of the event
            $table->string('name'); // Name of the event
            $table->enum('visibility', ['public', 'private']); // Public or private visibility
            $table->boolean('is_deleted')->default(false); // Soft delete flag
            $table->unsignedBigInteger('venue_id'); // Venue foreign key
            $table->unsignedBigInteger('organizer_id'); // Organizer foreign key
            $table->timestamps(); // Created at and updated at
        });

        // Add foreign key constraints after table creation
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('venue_id')->references('venue_id')->on('venues')->onDelete('cascade');
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
