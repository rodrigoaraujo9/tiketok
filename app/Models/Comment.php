<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id'; 
    public $timestamps = false;

    protected $fillable = [
        'content', 
        'date', 
        'event_id', 
        'user_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function poll()
    {
        return $this->hasOne(Poll::class, 'comment_id', 'comment_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }
}
