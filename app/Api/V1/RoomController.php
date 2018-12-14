<?php

namespace App\API\V1;

use App\Classes\JsonResponse;
use App\Models\Invite;
use App\Models\Room;
use App\Models\UserRoom;
use App\Services\MatchService;
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
            $room->load([
                'members' => function ($query) {
                    $query->orderBy('join_at', 'desc');
                },
                'members.wishlists' => function ($query) use ($room) {
                    $query->where('room_id', $room->id);
                }
            ]);
            $room->load('invites');
            $room['wishlists'] = WishlistService::find($room, $request->user());
            $room['matchReady'] = RoomService::isWishlistFulfilled($room);
            $room['matched'] = RoomService::isMatched($room);

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
                $developerMsg = 'Join room success';
                $userMsg = "Successfully joined room";
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

    public function matchInit(Request $request, Room $room)
    {
        try {
            //Check isHost
            if ($request->get('user')->id !== $room->created_by)
                throw new \Exception('You are not authorised to draw match');

            //Check wishlists are filled
            if (!RoomService::isWishlistFulfilled($room))
                throw new \Exception('Your room is not ready. Please have everyone fill in their wishlists first');

            $data = MatchService::match($room->id);

            $userMsg = $developerMsg = 'Room users matched successfully';
            return JsonResponse::success($developerMsg, $userMsg, $data);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = $e->getMessage();
            return JsonResponse::error($developerMsg, $userMsg);
        }
    }

    public function match(Request $request, Room $room)
    {
        try {
            $user = $request->user();
            $match = MatchService::find($room, $user);
            $wishlist = MatchService::wishlist($room, $user);
            $data = new \stdClass();
            $data->match = $match;
            $data->wishlist = $wishlist;

            $userMsg = $developerMsg = 'Retrieved match successfully';
            return JsonResponse::success($developerMsg, $userMsg, $data);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = 'Error has occurred';
            return JsonResponse::error($developerMsg, $userMsg);
        }
    }

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