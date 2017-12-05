<?php

namespace App\Classes;

class JsonResponse
{

    /**
     * @param $developerMsg
     * @param $userMsg
     * @param int $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($developerMsg, $userMsg, $errorCode = 400)
    {
        return self::sent([
            'developerMessage' => $developerMsg,
            'userMessage'      => $userMsg,
            'errorCode'        => $errorCode
        ], $errorCode);
    }

    /**
     * @param $developerMsg
     * @param $userMsg
     * @param int $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validateError($developerMsg, $userMsg, $errorCode = 422)
    {
        return self::sent([
            'developerMessage' => $developerMsg,
            'userMessage'      => $userMsg,
            'errorCode'        => $errorCode
        ], $errorCode);
    }

    /**
     * @param $developerMsg
     * @param $userMsg
     * @param null $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($developerMsg, $userMsg, $data = null, $code = 200)
    {
        return self::sent([
            'developerMessage' => $developerMsg,
            'userMessage'      => $userMsg,
            'code'             => $code,
            'data'             => $data
        ], $code);
    }

    /**
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sent($data, $code)
    {
        return response()->json($data, $code);
    }


}