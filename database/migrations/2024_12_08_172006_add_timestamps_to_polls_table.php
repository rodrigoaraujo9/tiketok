<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToPollsTable extends Migration
{
    public function up()
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropTimestamps(); // removes them
        });
    }
}


