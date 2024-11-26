<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password', 
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the cards for a user.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
    public function attendingEvents()
    {
        return $this->belongsToMany(Event::class, 'attends', 'user_id', 'event_id')
            ->withPivot('joined_at');
    }
    public function attendees()
    {
        return $this->belongsToMany(Event::class, 'attends', 'user_id', 'event_id')
            ->withPivot('joined_at');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }
}
