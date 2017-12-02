<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Wishlist extends Model
{
    protected $fillable = [
        'description',
        'user_id',
        'room_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id','id');
    }
}
