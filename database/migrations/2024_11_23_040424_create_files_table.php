<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('files')) {
            Schema::create('files', function (Blueprint $table) {
                $table->id('file_id');
                $table->string('url', 255)->unique();
                $table->unsignedBigInteger('comment_id');
                $table->timestamps();

                $table->foreign('comment_id')->references('comment_id')->on('comments')->cascadeOnDelete();
            });
        }
    }
};
