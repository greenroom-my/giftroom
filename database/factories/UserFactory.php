<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ? : $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Room::class, function (Faker $faker) {
    return [
        'name'             => $faker->name,
        'room_name'        => $faker->name,
        'room_description' => $faker->word,
        'budget'           => $faker->name,
        'event_day'        => $faker->date(),
        'created_by'       => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Models\UserRoom::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\Room::class)->create()->id;
        },
        'room_id' => function () {
            return factory(App\Models\Room::class)->create()->id;
        },
    ];
});

$factory->define(App\Models\Wishlist::class, function (Faker $faker) {
    return [
        'description'=> $faker->name,
        'user_id'=> 15,
        'room_id'=> 5,
    ];
});

$factory->define(App\Models\Invite::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'room_id'=> 5,
    ];
});

$factory->define(App\Models\Match::class, function (Faker $faker) {
    return [
        'santa_id' => 13,
        'target_id' => 15,
        'room_id' => 5,
    ];
});

//factory(App\Models\User::class, 1)
//    ->create()
//    ->each(function ($u) {
//        $u->posts()->save(factory(App\Post::class)->make());
//    });
//
//factory(App\Models\User::class, 1)->create()->each(function ($user) {
//    factory(App\Models\UserRoom::class, 2)->create(['user_id' => $user->id]);
//});