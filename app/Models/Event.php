<?php

namespace App\Models;

use App\Enums\EventTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations');
    }

    public function club()
    {
        return $this->belongsTo(User::class, 'club_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getImagePathAttribute()
    {
        return $this->image ? ltrim($this->image, '/') : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? route('event.image', ['path' => $this->image_path]) : null;
    }

    public function getRegisteredCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getCapacityLabelAttribute()
    {
        return $this->registered_count . '/' . $this->max_entries;
    }

    public function getTagsLabelsAttribute(): array
    {
        $tags = (array) ($this->tags ?? []);

        return array_values(array_filter(array_map(function ($tag) {
            $enum = EventTag::tryFrom($tag);

            return $enum ? $enum->label() : null;
        }, $tags)));
    }
}
