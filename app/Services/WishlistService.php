<?php

namespace App\Services;

use App\Models\Room;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

/**
 * Class WishlistService
 * @package App\Services
 */
class WishlistService
{
    /**
     * Pass room_id to this method to retrieve the wishlist of the user in this room
     * @return wishlist object
     */
    public static function find($room, $user)
    {
        return Wishlist::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->get();
    }

    /**
     * Arrays in an array
     * $attributes that passed to this method could contain more than one wishlist which include
     * description
     * @param $attributes
     * @param User $user
     * @param Room $room
     * @return array
     * @throws \Exception
     * @internal param $userId
     * @internal param $roomId
     */
    public static function create($attributes, User $user, Room $room)
    {
        DB::beginTransaction();
        try {
            if (count($attributes) > 3)
                throw new \Exception('Too many wishlist');

            if (count($attributes) < 3)
                throw new \Exception('Too litte wishlist');

            self::destroy($user);


            $wishlists = [];
            foreach ($attributes as $attribute) {
                $attribute['room_id'] = $room->id;
                $attribute['user_id'] = $user->id;
                array_push($wishlists, self::store($attribute));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Array
     * $attributes that passed to this method should contain only one wishlist which include
     * description
     * @return $room object
     */
    public static function store($attributes)
    {
        return Wishlist::create($attributes);
    }


    /**
     * @param $user
     */
    public static function destroy(User $user)
    {
        return Wishlist::where('user_id',$user->id)->delete();
    }
}