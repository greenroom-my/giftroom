<?php

namespace App\Services;

use App\Models\Room;
use Carbon\Carbon;

class RoomService
{
    /**
     * Randomize all friends in a room become array of matches
     * @return array
     */
    public static function randomizeMatches($roomId)
    {
        $friendIds = Room::where('id', $roomId)->first()->friends->pluck('id');

        $newArray = $pickedArray = [];

        for ($x = 0; $x < count($friendIds); $x++) {
            $temp = $friendIds;
            array_splice($temp, $x, 1);
            $temp = array_diff($temp, $pickedArray);
            $data = array_splice($temp, random_int(0, count($temp) - 1), 1);
            $data = array_shift($data);
            $newArray[ $friendIds["$x"] ] = $data;
            $pickedArray[] = $data;
        }

        return $newArray;
    }

    /**
     * $attributes that passed to this method should include
     * name and room_name
     *
     * @param $attributes
     * @param $user
     * @return mixed $room object
     */
    public static function create($attributes, $user)
    {
        $room = self::store($attributes, $user->id);

        $user->rooms()->attach($room->id, ['join_at' => new Carbon()]);

        return $room->fresh();
    }


    /**
     * store room detail in room db
     *
     * @param $attributes
     * @param $userId
     * @return mixed
     */
    public static function store($attributes, $userId)
    {
        $attributes['created_by'] = $userId;

        return Room::create($attributes);
    }

    /**
     * Pass room_id to this method to retrieve the whole room object
     * @return room object
     */
    public static function find($roomId)
    {
        return Room::find($roomId);
    }

    /**
     * Pass updated data to this method to update the whole room object
     * @return true/false
     */
    public static function update($attributes)
    {
        return Room::findOrFail($attributes['id'])
            ->update($attributes);
    }

}