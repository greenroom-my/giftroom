<?php

namespace App\API\V1;

use App\Classes\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\InviteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{

    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        try {
            $invites = InviteService::invite($request->email, $room);
            $developerMsg = $userMsg = "Invitation sent successfully.";

            return JsonResponse::success($developerMsg, $userMsg, $invites);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();

            return JsonResponse::error($developerMsg, "Invitation failed");
        }
    }

    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Room $room)
    {
        try {
            if(!$request->has('email'))
                throw new \Exception('Email is required');

            $invites = InviteService::uninvite($request->email, $room);
            $developerMsg = $userMsg = "Uninvited user successfully.";

            return JsonResponse::success($developerMsg, $userMsg, $invites);
        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();

            return JsonResponse::error($developerMsg, "Uninvite user failed");
        }
    }
}