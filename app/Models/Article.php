<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(User::class, 'club_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function canBeManagedBy(User $user): bool
    {
        if (in_array($user->role, ['admin', 'moderator'], true)) {
            return true;
        }

        if (in_array($user->role, ['club', 'club_moderator'], true)) {
            return $this->club_id && (int) $this->club_id === (int) ($user->club_id ?? $user->id);
        }

        return $user->id === $this->user_id;
    }
}
