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
        if (!Schema::hasTable('users')) { // Check if the users table already exists
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id'); // Primary key
                $table->string('username', 255)->unique(); // Unique username
                $table->string('email', 255)->unique(); // Unique email
                $table->string('name', 255); // User's name
                $table->string('phone', 15)->nullable(); // Phone number (optional)
                $table->string('profile_photo', 255)->nullable(); // Profile photo (optional)
                $table->string('password', 255); // Password
                $table->boolean('is_deleted')->default(false); // Soft delete
                $table->unsignedBigInteger('role_id')->default(1); // Foreign key for roles
                $table->rememberToken(); // Token for "Remember Me" functionality
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
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
