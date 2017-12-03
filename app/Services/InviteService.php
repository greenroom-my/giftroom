<?php

namespace App\Services;

use App\Models\Invite;

class InviteService
{
    /**
     * Pass an array of email that user want to invite
     */
    public static function invite($emails,$room_id)
    {
        foreach($emails as $email) {
            $room = Invite::create([$emails]);
        }
        return $room;
    }
}