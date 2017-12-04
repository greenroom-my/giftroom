<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\User;

class UserService
{
    /**
     * Pass user_id into this so you can retrieve list of rooms that the user are in
     *
     * @param $userId
     * @return array of rooms
     */
    public static function rooms($userId)
    {
        return User::where('id', $userId)
            ->first()
            ->rooms;
    }

    /**
     * @param User $user
     */
    public static function moveUserToRoom(User $user)
    {
        $userInvites = Invite::where('email', $user->email)->get();

        foreach ($userInvites as $userInvite) {
            $user->rooms()->attach($userInvite->room_id);
            $userInvite->delete();
        }
    }
}