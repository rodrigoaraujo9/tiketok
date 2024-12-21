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
                $table->id('event_id'); // Primary key
                $table->text('description');
                $table->timestamp('date')->notNull()->check('date >= CURRENT_DATE');
                $table->string('postal_code', 10)->nullable();
                $table->unsignedInteger('max_event_capacity')->nullable();
                $table->string('country');
                $table->string('name');
                $table->enum('visibility', ['public', 'private']);
                $table->boolean('is_deleted')->default(false);
                $table->unsignedBigInteger('venue_id');
                $table->unsignedBigInteger('organizer_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps();

                // Foreign keys
                $table->foreign('venue_id')->references('venue_id')->on('venues')->onDelete('cascade');
                $table->foreign('organizer_id')->references('user_id')->on('users')->onDelete('cascade'); // Correct column
                $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade'); // Correct column

            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
