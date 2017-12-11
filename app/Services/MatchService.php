<?php

namespace App\Services;

use App\Models\Match;
use App\Models\Room;

class MatchService
{
    /**
     * Pass the array of randomized user id into this function to create matches
     */
    public static function match($roomId)
    {
        $matches = self::random($roomId);

        foreach($matches as $key => $value) {
            Match::create([
                'santa_id' => $key,
                'target_id' => $value,
                'room_id' => $roomId
            ]);
        }

        return Match::where('room_id', $roomId)->get();
    }

    public static function find($room, $user) {
        $match = Match::where('room_id',$room->id)
            ->where('santa_id', $user->id)
            ->first();

        return UserService::find($match->target_id);
    }

    public static function wishlist($room, $user) {
        $match = self::find($room, $user);
        if ($match)
            return $match->wishlists;

        return false;
    }

    /**
     * Randomize all friends in a room become array of matches
     * @return array
     */
    public static function random($roomId)
    {
        $members = Room::where('id', $roomId)
            ->first()
            ->members
            ->pluck('id')
            ->toArray();

        $matches = $matchedArray = [];
        for ($x = 0; $x < count($members); $x++) {
            $temp = $members;
            array_splice($temp, $x, 1);
            $temp = array_diff($temp, $matchedArray);
            $data = array_splice($temp, random_int(0, count($temp) - 1), 1);
            $data = array_shift($data);
            $matches[$members["$x"]] = $data;
            $matchedArray[] = $data;
        }
    }

//    /**
//     * Pass room_id into this method to retrieve all pair of matches in this room
//     * @return $matches collections
//     */
//    public static function findAll($roomId)
//    {
//        return Match::where(['room_id', '=', $roomId])->get();
//    }
//
//    /**
//     * Pass room_id and santa_id into this method to retrieve specific room santa's target ID
//     * @return $matches object
//     */
//    public static function findTarget($roomId, $santaId)
//    {
//        return Match::where(['room_id', '=', $roomId],['santa_id', '=', $santaId])->first()->target_id;
//    }
//
//    /**
//     * Pass room_id and target_id into this method to retrieve specific room target's santa ID
//     * @return $matches object
//     */
//    public static function findSanta($roomId, $targetId)
//    {
//        return Match::where(['room_id', '=', $roomId],['target_id', '=', $targetId])->first()->santa_id;
//    }
//
//    /**
//     * Pass santa_id into this method to retrieve all target's matches
//     * @return $matches object
//     */
//    public static function findTargets($santaId)
//    {
//        return Match::where(['santa_id', '=', $santaId])->get();
//    }
}