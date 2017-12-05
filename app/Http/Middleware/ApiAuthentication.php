<?php
/**
 * Created by PhpStorm.
 * User: Monster
 * Date: 02/12/2017
 * Time: 7:32 PM
 */

namespace App\Http\Middleware;


use App\Classes\JsonResponse;
use App\Models\User;

class ApiAuthentication
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, \Closure $next)
    {
        // mei you gei http user id
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $developerMsg = "Headers did't not have user id";

            return JsonResponse::error($developerMsg, 'error', 401);
        }

        $userId = $_SERVER['HTTP_AUTHORIZATION'];
        // zhao bu dao user
        try {
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            return JsonResponse::error('error', 'error', 404);
        }

        $request->merge(['user' => $user]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

}