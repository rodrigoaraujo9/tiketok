<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    protected $table = 'poll_options';

    protected $primaryKey = 'option_id';

    protected $fillable = ['poll_id', 'option_text', 'votes'];

    public $timestamps = false;

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'poll_id');
    }


    public function votes()
    {
        return $this->hasMany(PollVote::class, 'option_id', 'option_id');
    }

}
