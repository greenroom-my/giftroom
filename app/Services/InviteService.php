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
     * @return string
     * @throws \Exception
     */
    public static function uninvite($email, $room, User $requestor)
    {
        $type = 'member';
        $invite = Invite::where('email', $email)->where('room_id', $room->id)->first();

        if ($invite) {
            $type = 'invite';
            $invite->delete();
        } else {
            $user = User::where('email', $email)->first();
            if ($user && $user->id !== $requestor->id)
                $user->rooms()->detach($room->id);
        }

        return $type;
    }
}