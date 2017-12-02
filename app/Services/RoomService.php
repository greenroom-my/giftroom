<?php

namespace App\Service;

use App\Models\Room;

class RoomService
{
    public static function randomizer($roomId){
        $iDs = Room::where('id', $roomId)->first()->friends->pluck('id');


    }
    /**
     * $attributes that passed to this method should include
     * name and room_name
     * @return $room object
     */
    public static function create($attributes,$userId)
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