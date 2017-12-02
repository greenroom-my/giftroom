<?php

namespace App\Service;

use App\Models\Wishlist;

class WishlistService
{
    /**
     * Array
     * $attributes that passed to this method should contain only one wishlist which include
     * description
     * @return $room object
     */
    public static function create($attributes,$userId)
    {
        $attributes['user_id'] = $userId;
        return Wishlist::create($attributes);
    }

    /**
     * Arrays in an array
     * $attributes that passed to this method could contain more than one wishlist which include
     * description
     * @return true
     */
    public static function createMany($attributes,$userId)
    {
        foreach ($attributes as $attribute) {
            $attribute['user_id'] = $userId;
            Wishlist::create($attribute);
        }
        return true;
    }

    /**
     * Pass room_id to this method to retrieve the wishlist of the user in this room
     * @return wishlist object
     */
    public static function find($roomId,$userId)
    {
        return Wishlist::where(['room_id','=',$roomId],['user_id','=', $userId])->get();
    }

    /**
     * Array
     * Pass updated data to this method to update the whole wishlist object
     * @return true/false
     */
    public static function update($attributes)
    {
        return Wishlist::findOrFail($attributes['id'])
            ->update($attributes);
    }

    /**
     * Arrays in an Array
     * Pass updated data to this method to update the whole wishlist object
     * @return true/false
     */
    public static function updateMany($attributes)
    {
        return Wishlist::findOrFail($attributes['id'])
            ->update($attributes);
    }

}