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
            $table->id('user_id'); // Ensure this matches the foreign key in `events`
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('phone', 15)->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('password');
            $table->boolean('is_deleted')->default(false);
            $table->unsignedBigInteger('role_id')->default(1);
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
        });
    }

};
