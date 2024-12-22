<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEventsAndTags extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
        });
    }
};
