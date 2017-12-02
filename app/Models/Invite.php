<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Invite extends Model
{
    protected $fillable = [
        'room_id',
        'email',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
