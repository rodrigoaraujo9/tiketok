<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToPollOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('poll_options', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('poll_options', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

};
