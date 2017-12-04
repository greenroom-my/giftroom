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
     */
    public static function uninvited($email)
    {
        // todo add uninvited
    }
}