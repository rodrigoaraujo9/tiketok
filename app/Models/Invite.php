<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $table = 'invites';

    // Specify the primary key
    protected $primaryKey = 'invite_id';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
    ];

    /**
     * Relationship: Invite belongs to an Event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
