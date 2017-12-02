<?php
/**
 * Created by PhpStorm.
 * User: Monster
 * Date: 02/12/2017
 * Time: 7:02 PM
 */

namespace App\API\V1;


use App\Models\Room;
use Illuminate\Http\Request;

class RoomController
{
    public function getRoom(Request $request, $name)
    {
//        dd($request->user());
        dd($name);
    }
}