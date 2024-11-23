<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('invites')) {
            Schema::create('invites', function (Blueprint $table) {
                $table->id('invite_id');
                $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamp('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->string('status', 50)->default('pending'); // e.g., 'pending', 'accepted', 'declined'
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
}
