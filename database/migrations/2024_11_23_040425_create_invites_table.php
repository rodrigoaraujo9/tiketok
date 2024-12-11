<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('invites')) {
            Schema::create('invites', function (Blueprint $table) {
                $table->id('invite_id');
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->string('status', 50)->default('pending');
                $table->timestamps();

                $table->foreign('event_id')->references('event_id')->on('events')->cascadeOnDelete();
                $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            });
        }
    }
};
