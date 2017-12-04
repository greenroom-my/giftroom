<?php

namespace App\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\InviteService;
use Illuminate\Http\Request;

class InviteController extends Controller
{

    /**
     * @param Request $request
     * @param Room $room
     */
    public function invite(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array',
        ]);

        if ($validator->fails()) {
            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $developerMsg = $userMsg = "Invitation sent successfully";

        DB::beginTransaction();
        try {
            $invites = InviteService::invite($request['emails'], $room->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $developerMsg = $e->getMessage();

            return JsonResponse::error($developerMsg, "Your invitation failed");
        }

        return JsonResponse::success($developerMsg, $userMsg, $invites);
    }

    /**
     * @param Request $request
     * @param Room $room
     */
    public function destroy(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        $developerMsg = $userMsg = "Invitation sent successfully";

        DB::beginTransaction();
        try {
            $invites = InviteService::uninvited($request['emails'], $room->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $developerMsg = $e->getMessage();

            return JsonResponse::error($developerMsg, "Your invitation failed");
        }

        return JsonResponse::success($developerMsg, $userMsg, $invites);

        // remove from invites
        // then remove from user_room
    }
}