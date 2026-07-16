<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getImagePathAttribute()
    {
        return $this->image ? ltrim($this->image, '/') : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? route('event.image', ['path' => $this->image_path]) : null;
    }
}
