<?php

namespace App\Services;

use App\Models\Wishlist;

class WishlistService
{

    /**
     * max wish list per user
     * @var int
     * */
    public $maxWishList;

    /**
     * WishlistService constructor.
     */
    public function __construct()
    {
        $this->maxWishList = 3;
    }

    /**
     * setter for wish list per user
     * @param $total
     * @return $this
     */
    public function setWishListTotal($total)
    {
        $this->maxWishList = $total;

        return $this;
    }

    /**
     * getter for total wish list number
     * @return int
     */
    public function getWishListTotal()
    {
        return $this->maxWishList;
    }

    /**
     * Array
     * $attributes that passed to this method should contain only one wishlist which include
     * description
     * @return $room object
     */
    public static function create($attributes, $userId)
    {
        $attributes['user_id'] = $userId;

        return Wishlist::create($attributes);
    }

    /**
     * Arrays in an array
     * $attributes that passed to this method could contain more than one wishlist which include
     * description
     * @param $attributes
     * @param $userId
     * @param $roomId
     * @return true
     */
    public static function createMany($attributes, $userId, $roomId)
    {
        $wishList = [];
        foreach ($attributes as $key => $attribute) {
            // if the wish list is more then 3 will not be saved
            // minus 1 is because index is start from 0
            if ($key >= (self::getWishListTotal() - 1)) continue;

            $attribute['room_id'] = $roomId;
            $attribute['user_id'] = $userId;

            $savedWishList = Wishlist::create($attribute);
            $wishList[] = $savedWishList->fresh();
        }

        return $wishList;
    }

    /**
     * Pass room_id to this method to retrieve the wishlist of the user in this room
     * @return wishlist object
     */
    public static function find($roomId, $userId)
    {
        return Wishlist::where(['room_id', '=', $roomId], ['user_id', '=', $userId])->get();
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