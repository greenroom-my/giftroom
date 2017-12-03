<?php
/**
 * Created by PhpStorm.
 * User: Monster
 * Date: 02/12/2017
 * Time: 7:02 PM
 */

namespace App\API\V1;


use App\Classes\JsonResponse;
use App\Models\Invite;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController
{
    const INVITED = 1;
    const CREATE_ROOM = 2;
    const JOIN_ROOM = 3;
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function roomInvited(Request $request)
    {
        $validator = $this->makeValidation(self::INVITED,$request);

        if ($validator->fails()) {

            $developerMsg = "Validation Error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $roomId = $request->room_id;
        $email = $request->email;

        try{
            $invited = Invite::create([
                'room_id'=> $roomId,
                'email' => $email
            ]);

            $developerMsg = "Invitation sent";
            $userMsg = "Send invitation success";

            return JsonResponse::success($developerMsg, $userMsg,$invited);

        }catch (\Exception $e){

            $developerMsg = $e->getMessage();
            $userMsg = "Your invitation failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }
    }


    public function createRoom(Request $request)
    {
        $validator = $this->makeValidation(self::CREATE_ROOM,$request);

        if ($validator->fails()) {

            $developerMsg = "Validation Error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        try{
            $invited = Room::create([
                'name'=> $request->name,
                'room_name' => $request->room_name,
                'room_description' => $request->room_description,
                'budget' => $request->budget,
                'event_day' => $request->event_day,
                'created_by' => $request->created_by,
            ]);

            $developerMsg = "Room is created";
            $userMsg = "Your room is created";

            return JsonResponse::success($developerMsg, $userMsg,$invited);

        }catch (\Exception $e){

            $developerMsg = $e->getMessage();
            $userMsg = "Your room create failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

    }

    protected function makeValidation($action ,Request $request)
    {
        switch ($action) {
            case $action == 1 :
                return $validatedData = Validator::make($request->all(), [
                    'room_id' => 'required|int',
                    'email' => 'required|string',
                ]);
                break;

            case $action == 2:
                return $validatedData = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'room_name' => 'required|string|unique:rooms',
                    'room_description' => 'required|string',
                    'budget' => 'required|string',
                    'event_day' => 'required|string',
                    'created_by' => 'required|int',
                ]);
                break;

            case $action == 3:

                break;

            default:

        }
    }
}