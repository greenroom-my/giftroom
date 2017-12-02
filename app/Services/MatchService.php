<?php

namespace App\Service;

use App\Models\Match;

class MatchService
{


    public static function create($attributes)
    {
        $attributes['user_id'] = Auth::user()->id;
        return Match::create($attributes);
    }

    public static function find($room_id)
    {
        return Match::where(['room_id','=',$room_id],['user_id','=', Auth::user()->id])->first();
    }

    public static function update($attributes)
    {
        return Match::findOrFail($attributes['id'])
            ->update($attributes);
    }
}