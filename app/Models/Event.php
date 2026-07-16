<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations');
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
}
