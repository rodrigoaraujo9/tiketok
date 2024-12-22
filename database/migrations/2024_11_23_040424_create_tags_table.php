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
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id('tag_id');
                $table->string('name')->unique();
                $table->unsignedBigInteger('event_id'); // Use unsignedBigInteger for consistency
                $table->timestamps();
            });
        }
    }

};
