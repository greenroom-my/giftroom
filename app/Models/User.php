<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'rooms'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'user_rooms', 'user_id', 'room_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'id');
    }

    public function santa()
    {
        return $this->hasMany(Match::class, 'santa_id', 'id');
    }

    public function target()
    {
        return $this->hasMany(Match::class, 'target_id', 'id');
    }
}
