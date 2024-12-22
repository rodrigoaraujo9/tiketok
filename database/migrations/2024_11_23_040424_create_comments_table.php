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
        if (Schema::hasTable('events') && !Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id('comment_id');
                $table->text('content');
                $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('event_id');
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('user_id')->on('users')
                    ->onDelete('cascade');
                    
                $table->foreign('event_id')
                    ->references('event_id')->on('events')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
