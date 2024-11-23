<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('befriends', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id_1');
            $table->unsignedBigInteger('user_id_2');
            $table->primary(['user_id_1', 'user_id_2']);

            $table->foreign('user_id_1')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('user_id_2')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }
};
