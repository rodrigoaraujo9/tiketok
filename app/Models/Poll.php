<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'comment_id',
        'question',
        'user_id',
        'end_date'
    ];
    
    protected static function boot()
    {
        parent::boot();
    
        static::saving(function ($poll) {
            if ($poll->event_id && $poll->comment_id) {
                throw new \Exception('A poll cannot be associated with both an event and a comment.');
            }
    
            if (!$poll->event_id && !$poll->comment_id) {
                throw new \Exception('A poll must be associated with either an event or a comment.');
            }
        });
    }
    

    protected $primaryKey = 'poll_id'; 

    public $timestamps = true;

    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id', 'poll_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function userHasVoted($userId)
    {
        return PollVote::where('poll_id', $this->poll_id)
            ->where('user_id', $userId)
            ->exists();
    }


    public function votes()
    {
        return $this->hasMany(PollVote::class, 'poll_id', 'poll_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }


}

