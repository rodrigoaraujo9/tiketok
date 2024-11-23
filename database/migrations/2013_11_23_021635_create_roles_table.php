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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Primary key
            $table->string('username', 255)->unique(); // Unique username
            $table->string('email', 255)->unique(); // Unique email
            $table->string('name', 255); // User's name
            $table->string('phone', 15)->nullable(); // Optional phone number
            $table->string('profile_photo', 255)->nullable(); // Optional profile photo
            $table->string('password', 255); // User password
            $table->boolean('is_deleted')->default(false); // Soft delete flag
            $table->rememberToken(); // For "Remember Me" functionality
            $table->timestamps(); // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
/**
 * Reverse the migrations.
 */
/**
 * Reverse the migrations.
 */
/**
 * Reverse the migrations.
 */
/**
 * Reverse the migrations.
 */
/**
 * Reverse the migrations.
 */
/**
 * Reverse the migrations.
 */
public function down(): void
{
    // Drop all dependent tables first in the correct order
 

    // Finally drop the `users` table
    Schema::dropIfExists('users');
}


};
