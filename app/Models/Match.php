<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Match extends Model
{
    protected $fillable = [
        'santa_id',
        'target_id',
        'room_id',
    ];

    public function santa()
    {
        return $this->belongsTo(User::class, 'santa_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
