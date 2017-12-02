<?php

/**
 * Created by PhpStorm.
 * User: Monster
 * Date: 02/12/2017
 * Time: 1:21 PM
 */

namespace App\API\V1\Auth;

use App\Classes\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function register(Request $request)
    {

//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:6|confirmed',
//        ]);


        $validator = $this->validator($request->all());

        if ($validator->fails()) {

            $developerMsg = "Validation Error!";

            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        try {

            if ($user = $this->create($request->all())) {

                $developerMsg = "success!";
                $userMsg = "Registration successfully!";

                return JsonResponse::success($developerMsg, $userMsg, $user);
            };

        } catch (\Exception $e) {

            $developerMsg = $e->getMessage();

            $userMsg = "Failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

    }

    protected function validator(array $data)
    {

        return $validatedData = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}