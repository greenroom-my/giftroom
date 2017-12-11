<?php

namespace App\Services;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class RoomService
{
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
        DB::beginTransaction();
        try {
            $room = self::store($attributes, $user->id);
            self::attachUser($room, $user->id);
            self::joinRoom($room, $user);

            DB::commit();
            return $room->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
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
        return Room::with('members', 'invites')
            ->find($roomId);
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

    /**
     * @param Room $room
     * @param $userId
     * @return Room
     */
    public static function attachUser(Room $room, $userId)
    {
        $room->members()->attach($userId);
        return $room;
    }

    public static function joinRoom(Room $room, User $user)
    {
        $room->members()->updateExistingPivot($user->id, ['join_at' => Carbon::now()]);
        return $room;
    }

    public static function userInRoom(Room $room, User $user)
    {
        $test = false;
        foreach ($user->availableRooms as $userRoom) {
            if ($userRoom->name == $room->name)
                $test = $room;
        }

        return $test;
//        $room = $room->whereHas('members', function ($query) use ($user) {
//            $query->where('users.id', '=' , $user->id);
//        })->first();
    }

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
            $newArray[$friendIds["$x"]] = $data;
            $pickedArray[] = $data;
        }

        return $newArray;
    }
}