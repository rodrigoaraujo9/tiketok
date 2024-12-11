<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEndDateDefaultInPollsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE polls ALTER COLUMN end_date SET DEFAULT (CURRENT_TIMESTAMP + INTERVAL '30 days')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE polls ALTER COLUMN end_date DROP DEFAULT");
    }
}

