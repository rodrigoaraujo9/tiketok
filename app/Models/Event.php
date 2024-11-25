<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false; 

    // Specify the custom primary key
    protected $primaryKey = 'event_id';

    // Ensure Laravel knows it's not an auto-incrementing integer if it's a string
    protected $keyType = 'int'; 

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'description',
        'date',
        'postal_code',
        'max_event_capacity',
        'country',
        'visibility',
        'venue_id',
        'organizer_id',
    ];

    protected $casts = [
        'visibility' => 'boolean', // Cast to boolean
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id', 'user_id');
    }
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'attends', 'event_id', 'user_id')
            ->withPivot('joined_at');
    }
    
    public function comments()
{
    return $this->hasMany(Comment::class, 'event_id', 'event_id');
}


}
