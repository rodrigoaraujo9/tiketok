<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasFactory;

    protected $table = 'poll_votes'; 
    protected $primaryKey = 'vote_id'; 

    protected $fillable = [
        'poll_id',
        'option_id',
        'user_id',
    ];

    public $timestamps = false;
}
