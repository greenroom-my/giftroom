<?php

namespace App\API\V1;

use App\Classes\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Wishlist;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Room $room)
    {
        $user = $request->user();

        $wishList = Wishlist::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->get();

        $developerMsg = 'Success';
        $userMsg = 'Retrieved room successfully';

        return JsonResponse::success($developerMsg, $userMsg, $wishList);
    }

    /**
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'wishlists' => 'required|array'
        ]);

        if ($validator->fails()) {
            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        try {
            $wishList = WishlistService::create(
                $request->get('wishlists'),
                $request->user(),
                $room);

            $developerMsg = $userMsg = 'Wish list saved successfully';
            return JsonResponse::success($developerMsg, $userMsg, $wishList);

        } catch (\Exception $e) {
            $developerMsg = $e->getMessage();
            $userMsg = 'Wish List save failed';

            return JsonResponse::error($developerMsg, $userMsg);
        }
    }
}