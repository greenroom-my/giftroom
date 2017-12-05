<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\Room;
use App\Models\User;

class InviteService
{
    /**
     * Pass an array of email that user want to invite
     *
     * @param $email
     * @param Room $room
     * @return array
     */
    public static function invite($email, Room $room)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            RoomService::attachUser($room, $user->id);
            $response = $user;
        } else {
            $response = Invite::create([
                'email'   => $email,
                'room_id' => $room->id
            ]);
        }

        return $response;
    }

    /**
     * unlike invite this will only delete one user at a time
     *
     * @param $email
     * @param $room
     * @throws \Exception
     */
    public static function uninvite($email, $room)
    {
        $invite = Invite::where('email', $email)->where('room_id', $room->id)->first();

        if ($invite)
            return $invite->delete();
        else {
            $user = User::where('email', $email)->first();
            if ($user)
                return $user->rooms()->detach($room->id);
        }
    }
}