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

Route::POST('/user/register', 'Auth\RegisterController@register');
Route::POST('/user/login', 'Auth\LoginController@login');

Route::middleware(['api', 'api.auth'])->group(function () {
    Route::GET('/room/{name}', 'RoomController@getRoom');
    Route::GET('/room/{name}/matches', 'RoomController@getRoomMatches');
    Route::GET('/room/{name}/my-wish-list', 'WishListController@getOwnWishList');

    Route::POST('/room/{name}/invites', 'InviteController@invite');
    Route::DELETE('/room/{name}/invites', 'InviteController@destroy');

    Route::POST('/room', 'RoomController@store');
    // Route::POST('/room/add-friend', 'RoomController@addFriend');
    Route::POST('/room/{name}/my-wish-list');

    Route::PATCH('/room');
    Route::PATCH('/room/join');
    Route::PATCH('/room/{name}/my-wish-list');

    Route::DELETE('/user/{name}/invites');
});
