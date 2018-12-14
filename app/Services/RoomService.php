<?php

namespace App\Services;

use App\Models\Match;
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
    }

    public static function isWishlistFulfilled(Room $room)
    {
        $members = $room->members->load('wishlists');
        $unfilledMembers = $members->filter(function($member) {
            if(count($member->wishlists) == 0)
                return $member;
        });

        if(count($unfilledMembers) > 0 || count($room->invites) > 0)
            return false;

        return true;
    }

    public static function isMatched(Room $room)
    {
        if (!self::isWishlistFulfilled($room))
            return false;

        $matchExist = Match::where('room_id', $room->id)->first();
        if(!$matchExist)
            return false;

        return true;
    }
}