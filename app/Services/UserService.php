<?php

namespace App\Service;

use App\Models\User;

class UserService
{
    /**
     * Pass user_id into this so you can retrieve list of rooms that the user are in
     * @return array of rooms
     */
    public static function rooms($userId)
    {
        return User::where('id', $userId)
            ->first()
            ->rooms;
    }

}