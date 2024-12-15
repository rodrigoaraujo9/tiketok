<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('poll_votes')) {
            Schema::create('poll_votes', function (Blueprint $table) {
                $table->id('vote_id');
                $table->unsignedBigInteger('poll_id');
                $table->unsignedBigInteger('option_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                // Foreign keys
                $table->foreign('poll_id')->references('poll_id')->on('polls')->cascadeOnDelete();
                $table->foreign('option_id')->references('option_id')->on('poll_options')->cascadeOnDelete();
                $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();

                $table->unique(['poll_id', 'user_id'], 'unique_user_vote_per_poll');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
    }
};
