<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubMembership extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(User::class, 'club_id');
    }
}
