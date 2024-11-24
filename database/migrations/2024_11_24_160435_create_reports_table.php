<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->text('reason');
            $table->string('r_status')->default('pending'); // Status: pending, reviewed, resolved
            $table->timestamps();

            // Foreign keys
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}

