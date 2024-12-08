<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'question'];

    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id');
    }

}
