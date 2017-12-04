<?php

namespace App\Services;

use App\Models\Match;

class MatchService
{


    /**
     * Pass the array of randomized user id into this function to create matches
     */
    public static function create($roomId)
    {
        $randomizeMatches = RoomService::randomizeMatches($roomId);
        foreach ($randomizeMatches as $santa => $target) {
            Match::create([
                'santa_id'  => $santa,
                'target_id' => $target,
                'room_id'   => $roomId,
            ]);
        }
    }

    /**
     * Pass room_id into this method to retrieve all pair of matches in this room
     * @return $matches collections
     */
    public static function findAll($roomId)
    {
        return Match::where(['room_id', '=', $roomId])->get();
    }

    /**
     * Pass room_id and santa_id into this method to retrieve specific room santa's target ID
     * @return $matches object
     */
    public static function findTarget($roomId, $santaId)
    {
        return Match::where(['room_id', '=', $roomId],['santa_id', '=', $santaId])->first()->target_id;
    }

    /**
     * Pass room_id and target_id into this method to retrieve specific room target's santa ID
     * @return $matches object
     */
    public static function findSanta($roomId, $targetId)
    {
        return Match::where(['room_id', '=', $roomId],['target_id', '=', $targetId])->first()->santa_id;
    }

    /**
     * Pass santa_id into this method to retrieve all target's matches
     * @return $matches object
     */
    public static function findTargets($santaId)
    {
        return Match::where(['santa_id', '=', $santaId])->get();
    }



}