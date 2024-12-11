<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attends')) {
            Schema::create('attends', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('event_id');
                $table->primary(['user_id', 'event_id']);
                $table->timestamp('joined_at')->default(DB::raw('CURRENT_TIMESTAMP'));

                $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
                $table->foreign('event_id')->references('event_id')->on('events')->cascadeOnDelete();
            });
        }
    }
};
