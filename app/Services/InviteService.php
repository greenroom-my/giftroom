<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\User;

class InviteService
{
    /**
     * Pass an array of email that user want to invite
     *
     * @param array $emails
     * @param $roomId
     * @return array
     */
    public static function invite(array $emails, $roomId)
    {
        $invites = [];
        foreach ($emails as $email) {
            // check is user exist
            $user = User::where('email', $email)->first();
            if ($user) {
                RoomService::attachUserInARoom($user->id, $roomId);
                // update user details as invite so that it can be attach
                $invite = $user;
            } else {
                // create invites if the email does not exist in the system
                $invite = Invite::create([
                    'email'   => $email,
                    'room_id' => $roomId
                ]);
            }

            $invites[] = $invite->fresh();
        }

        return $invites;
    }

    /**
     * unlike invite this will only delete one user at a time
     *
     * @param $email
     * @param $room
     * @throws \Exception
     */
    public static function uninvited($email, $room)
    {
        // if the user does not register in the whole event
        // then the entry should be still in invites table instead of user_rooms
        $invite = Invite::where('email', $email)->where('room_id', $room->id)->first();

        // if invites then will delete the entry
        // if not in invites mean user have already register and the entry will be in user_room instead
        // then will need to detach from there
        if ($invite) $invite->delete();
        else {
            $user = User::where('email', $email)->first();
            if($user)
                $user->rooms()->detach($room->id);
        }
    }
}