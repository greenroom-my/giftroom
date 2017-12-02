<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserRoom extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'join_at',
    ];
}
