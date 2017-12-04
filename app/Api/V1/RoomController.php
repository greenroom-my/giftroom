<?php

namespace App\API\V1;

use App\Classes\JsonResponse;
use App\Models\Invite;
use App\Models\Room;
use App\Models\UserRoom;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController
{
    const INVITED = 1;
    const CREATE_ROOM = 2;
    const JOIN_ROOM = 3;
    const ADD_FRIEND = 4;

    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Room $room)
    {
        try {
            $userMsg = $developerMsg = 'Room does not exists';

            $room->load('members');
            $room->load('invites');

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
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomMatches(Request $request, $name)
    {

        $room = Room::with(['matches' => function ($q) {
            $q->with('santa', 'target');
        }])->where('room_name', $name)->first();

        if ( !isset($room)) {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function roomInvited(Request $request)
    {
        $validator = $this->makeValidation(self::INVITED, $request);

        if ($validator->fails()) {

            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $roomId = $request->room_id;
        $email = $request->email;

        try {
            $invited = Invite::create([
                'room_id' => $roomId,
                'email'   => $email
            ]);

            $developerMsg = "Invitation sent";
            $userMsg = "Send invitation success";

            return JsonResponse::success($developerMsg, $userMsg, $invited);

        } catch (\Exception $e) {

            $developerMsg = $e->getMessage();
            $userMsg = "Your invitation failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->has('name')) {
            $sanitizeName = sanitize($request->name);
            $request->replace(['name' => $sanitizeName]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:rooms,name',
        ]);

        if ($validator->fails()) {

            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $user = $request->user();
        try {
            $room = RoomService::create($request->all(), $user);

            $developerMsg = "Room is created";
            $userMsg = "Your room is created";

            return JsonResponse::success($developerMsg, $userMsg, $room);

        } catch (\Exception $e) {

            $developerMsg = $e->getMessage();
            $userMsg = "Your room create failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

    }

    public function addFriend(Request $request)
    {
        $validator = $this->makeValidation(self::ADD_FRIEND, $request);

        if ($validator->fails()) {

            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $userId = $request->user_id;
        $roomId = $request->room_id;

        $userExistsRoom = UserRoom::where('user_id', $userId)->where('room_id', $roomId)->first();

        if ($userExistsRoom) {

            $developerMsg = 'User already in the room';
            $userMsg = "The user already exists the room";

            return JsonResponse::error($developerMsg, $userMsg, 401);
        }

        try {
            $userRoom = new UserRoom();

            $userRoom->user_id = $request->user_id;
            $userRoom->room_id = $request->room_id;
            if (isset($request->join_at))
                $userRoom->join_at = $request->join_at;

            $userRoom->save();

            $developerMsg = "Add friend successful";
            $userMsg = "You friend is added in this room";

            return JsonResponse::success($developerMsg, $userMsg, $userRoom);

        } catch (\Exception $e) {

            $developerMsg = $e->getMessage();
            $userMsg = "Add friend failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

    }

    protected function makeValidation($action, Request $request)
    {
        switch ($action) {
            case 1:
                return $validatedData = Validator::make($request->all(), [
                    'room_id' => 'required|int',
                    'email'   => 'required|string',
                ]);
                break;

            case 4:
                return $validatedData = Validator::make($request->all(), [
                    'user_id' => 'required|int',
                    'room_id' => 'required|int'
                ]);
                break;
        }
    }
}