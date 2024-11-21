<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Event extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'date',
        'postalCode',
        'maxEventCapacity',
        'country',
        'visibility',
        'venue_id',
        'organizer_id',
    ];

    /**
     * The organizer of the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * The venue associated with the event.
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * The attendees of the event.
     */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attends', 'event_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Tickets for the event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
