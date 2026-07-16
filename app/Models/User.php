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
        'description',
        'avatar',
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

    public function clubMemberships()
    {
        return $this->hasMany(ClubMembership::class);
    }

    public function joinedClubs()
    {
        return $this->belongsToMany(User::class, 'club_memberships', 'user_id', 'club_id');
    }

    public function clubEvents()
    {
        return Event::query()
            ->whereIn('club_id', $this->joinedClubs()->pluck('users.id'))
            ->whereNotIn('id', $this->registeredEvents()->pluck('events.id'));
    }

    public function canAccessClubContent($clubId): bool
    {
        if (! $clubId) {
            return true;
        }

        if (! $this->club_banned) {
            return true;
        }

        return ! ($this->club_ban_club_id && (int) $this->club_ban_club_id === (int) $clubId);
    }

    public function canParticipateInClubEvent($event): bool
    {
        return $this->canAccessClubContent($event->club_id);
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

    public function canManageBookExchange($exchange): bool
    {
        if (in_array($this->role, ['admin', 'moderator'], true)) {
            return true;
        }

        return $this->id === $exchange->user_id;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getAvatarPathAttribute()
    {
        return $this->avatar ? ltrim($this->avatar, '/') : null;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? route('profile.avatar', ['path' => $this->avatar_path]) : null;
    }

    public function canDeleteComment($comment): bool
    {
        if (in_array($this->role, ['admin', 'moderator'], true)) {
            return true;
        }

        if ($this->id === $comment->user_id) {
            return true;
        }

        if ($comment->event_id && $comment->event && $this->canManageEvent($comment->event)) {
            return true;
        }

        if ($comment->profile_user_id && $comment->profile_user_id === $this->id) {
            return true;
        }

        return false;
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
