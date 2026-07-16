<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
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

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function hasRegistered($eventId)
    {
        return $this->registrations()->where('event_id', $eventId)->exists();
    }

    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_registrations');
    }

    public function canManageEvent($event): bool
    {
        $role = $this->role;

        if (in_array($role, ['admin', 'moderator'], true)) {
            return true;
        }

        if ($role === 'club') {
            return $event->club_id && (int) $event->club_id === (int) $this->id;
        }

        if ($role === 'club_moderator') {
            return $event->club_id && (int) $event->club_id === (int) $this->club_id;
        }

        return false;
    }

    public function canCreateEvents(): bool
    {
        return in_array($this->role, ['admin', 'moderator', 'club', 'club_moderator'], true);
    }

    public function canManageClub($club): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role === 'club' && $this->id === $club->id) {
            return true;
        }

        return $this->role === 'club_moderator'
            && $this->club_id
            && (int) $this->club_id === (int) $club->id;
    }

}
