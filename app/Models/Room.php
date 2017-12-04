<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    protected $fillable = [
        'name',
        'room_id',
        'description',
        'budget',
        'event_day',
        'created_by',
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'user_rooms', 'room_id', 'user_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'room_id', 'id');
    }

    public function invites()
    {
        return $this->hasMany(Invite::class, 'room_id', 'id');
    }

    public function matches()
    {
        return $this->hasMany(Match::class, 'room_id', 'id');
    }
}
