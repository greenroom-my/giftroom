<?php

namespace App\API\V1;

use App\Classes\JsonResponse;
use App\Models\Invite;
use App\Models\Room;
use App\Models\UserRoom;
use App\Services\RoomService;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController
{
    const INVITED = 1;
    const CREATE_ROOM = 2;
    const JOIN_ROOM = 3;


    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Room $room)
    {
        try {
            $room->load(['members' => function ($query) {
                $query->orderBy('join_at', 'desc');
            }]);
            $room->load('invites');
            $room['wishlists'] = WishlistService::find($room, $request->user());

            $userMsg = $developerMsg = 'Retrieved room successfully';
            return JsonResponse::success($developerMsg, $userMsg, $room);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = 'Error has occurred';
            return JsonResponse::error($developerMsg, $userMsg, $room);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:rooms,name',
        ]);

        if ($validator->fails()) {

            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        try {
            $attributes = $request->all();
            if ($request->has('name')) {
                $attributes['name'] = sanitize($attributes['name']);
            }

            $room = RoomService::create(
                $attributes,
                $user = $request->user()
            );
            $room->load('members');
            $room->load('invites');

            $developerMsg = "Room is created";
            $userMsg = "Your room is created";

            return JsonResponse::success($developerMsg, $userMsg, $room);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = "Your room create failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

    }

    public function join(Request $request, Room $room)
    {
        try {
            if (RoomService::userInRoom($room, $request->user())) {
                $room = RoomService::joinRoom($room, $request->user());
                $developerMsg = 'User already in the room';
                $userMsg = "The user already exists the room";
                return JsonResponse::success($developerMsg, $userMsg, $room);
            } else {
                $developerMsg = "User not invited";
                $userMsg = "You are not invited to join this room";
                return JsonResponse::error($developerMsg, $userMsg);
            }
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = "Add member failed";
            return JsonResponse::error($developerMsg, $userMsg);
        }
    }

//    /**
//     * @param Request $request
//     * @param $name
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function getRoomMatches(Request $request, $name)
//    {
//
//        $room = Room::with(['matches' => function ($q) {
//            $q->with('santa', 'target');
//        }])->where('room_name', $name)->first();
//
//        if ( !isset($room)) {
//            $developerMsg = 'Room not exists';
//            $userMsg = 'Room is don\'t not exits';
//
//            return JsonResponse::error($developerMsg, $userMsg);
//        }
//
//        if (isset($room->matches) && $room->matches->isNotEmpty()) {
//
//            $data = $room->matches;
//            $developerMsg = 'Success';
//            $userMsg = 'Matches is exists';
//
//            return JsonResponse::success($developerMsg, $userMsg, $data);
//        }
//
//        $developerMsg = 'Matches not exists';
//        $userMsg = 'The room have\'t have matches';
//
//        return JsonResponse::error($developerMsg, $userMsg);
//
//    }

//    /**
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function roomInvited(Request $request)
//    {
//        $validator = $this->makeValidation(self::INVITED, $request);
//
//        if ($validator->fails()) {
//
//            $developerMsg = "Validation error";
//            $userMsg = $validator->errors();
//
//            return JsonResponse::validateError($developerMsg, $userMsg);
//        }
//
//        $roomId = $request->room_id;
//        $email = $request->email;
//
//        try {
//            $invited = Invite::create([
//                'room_id' => $roomId,
//                'email'   => $email
//            ]);
//
//            $developerMsg = "Invitation sent";
//            $userMsg = "Send invitation success";
//
//            return JsonResponse::success($developerMsg, $userMsg, $invited);
//
//        } catch (\Exception $e) {
//
//            $developerMsg = $e->getMessage();
//            $userMsg = "Your invitation failed";
//
//            return JsonResponse::error($developerMsg, $userMsg);
//        }
//    }

    protected function makeValidation($action, Request $request)
    {
        switch ($action) {
            case 1:
                return $validatedData = Validator::make($request->all(), [
                    'room_id' => 'required|int',
                    'email' => 'required|string',
                ]);
                break;
        }
    }
}