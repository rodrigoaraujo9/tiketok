<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    // Disable timestamps for this model
    public $timestamps = false;
    protected $primaryKey = 'venue_id';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'location',
        'max_capacity',
    ];

    /**
     * Get the events associated with the venue.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'venue_id');
    }
}
