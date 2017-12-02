<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

Route::POST('user/register', '\App\Api\V1\Auth\RegisterController@register');
Route::POST('/user/login', '\App\Api\V1\Auth\LoginController@login');


Route::middleware(['api', 'api.auth'])->group(function () {

    Route::GET('/room/{name}', '\App\Api\V1\Auth\RoomController@getRoom');
    Route::GET('/room/room/{name}/matches');
    Route::GET('/room/my-wish-list');

    Route::POST('/room/invites');
    Route::POST('/room');
    Route::POST('/room/matches');
    Route::POST('/room/my-wish-list');

    Route::PATCH('/room');
    Route::PATCH('/room/join');
    Route::PATCH('/room/my-wish-list');

    Route::DELETE('/user/{roomName}/invites');
});
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
