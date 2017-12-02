<?php
/**
 * Created by PhpStorm.
 * User: Monster
 * Date: 02/12/2017
 * Time: 7:02 PM
 */

namespace App\API\V1;


use App\Classes\JsonResponse;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController
{
    /**
     * @param Request $request
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoom(Request $request, $name)
    {
        $room = Room::where('room_name', $name)->first();

        if (isset($room)) {
            $developerMsg = 'Success';
            $userMsg = 'Room is exists';
            return JsonResponse::success($developerMsg, $userMsg, $room);
        }

        $developerMsg = 'Room not exists';
        $userMsg = 'Room is don\'t not exists';
        return JsonResponse::error($developerMsg, $userMsg);
    }

    /**
     * @param Request $request
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomMatches(Request $request, $name)
    {

        $room = Room::with(['matches' => function ($q) {
            $q->with('santa', 'target');
        }])->where('room_name', $name)->first();

        if (!isset($room)) {
            $developerMsg = 'Room not exists';
            $userMsg = 'Room is don\'t not exits';
            return JsonResponse::error($developerMsg, $userMsg);
        }

        if (isset($room->matches) && $room->matches->isNotEmpty()) {

            $data = $room->matches;
            $developerMsg = 'Success';
            $userMsg = 'Matches is exists';

            return JsonResponse::success($developerMsg, $userMsg, $data);
        }

        $developerMsg = 'Matches not exists';
        $userMsg = 'The room have\'t have matches';
        return JsonResponse::error($developerMsg, $userMsg);

    }

    /**
     * @param Request $request
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOwnWishList(Request $request, $name)
    {
        $currentUserId = $request->user()->id;

        $room = Room::with(['wishlists' => function ($q) use ($currentUserId) {
            $q->where('user_id', $currentUserId);
        }])->where('room_name', $name)->first();


        if (!isset($room)) {
            $developerMsg = 'Room not exists';
            $userMsg = 'Room is don\'t not exits';
            return JsonResponse::error($developerMsg, $userMsg);
        }

        if (isset($room->wishlists) && $room->wishlists->isNotEmpty()) {

            $data = $room->wishlists;
            $developerMsg = 'Success';
            $userMsg = 'Your wish list is exists';

            return JsonResponse::success($developerMsg, $userMsg, $data);
        }

        $developerMsg = 'Wish list not exists';
        $userMsg = 'You did\'t have any wish list';
        return JsonResponse::error($developerMsg, $userMsg);
    }
}