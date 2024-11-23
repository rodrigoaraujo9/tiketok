<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAttendsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('attends')) {
            Schema::create('attends', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
                $table->timestamp('joined_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->primary(['user_id', 'event_id']);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('attends')) {
            Schema::dropIfExists('attends');
        }
    }
}
