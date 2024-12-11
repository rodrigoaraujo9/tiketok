<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'question'];

    protected $primaryKey = 'poll_id'; 

    public $timestamps = true;

    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id', 'poll_id');
    }
}

