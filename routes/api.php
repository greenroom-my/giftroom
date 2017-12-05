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
    Route::POST('/room', 'RoomController@store');
    Route::GET('/room/{room}', 'RoomController@index');

    Route::POST('/room/{room}/invites', 'InviteController@invite');
    Route::DELETE('/room/{room}/invites', 'InviteController@destroy');

    Route::GET('/room/{room}/my-wish-list', 'WishListController@index');
    Route::POST('/room/{name}/my-wish-list', 'WishListController@store');
    Route::PATCH('/room/{room}/my-wish-list', 'WishListController@edit');
});
