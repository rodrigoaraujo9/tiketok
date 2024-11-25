<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $primaryKey = 'report_id';
    
    protected $fillable = [
        'event_id',
        'user_id',
        'reason',
        'r_status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}