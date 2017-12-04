<?php

namespace App\API\V1\Auth;

use App\Classes\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        $developerMsg = "Success";
        $userMsg = "Registration successfully";

        if ($validator->fails()) {
            $developerMsg = "Validation error";
            $userMsg = $validator->errors();

            return JsonResponse::validateError($developerMsg, $userMsg);
        }

        DB::beginTransaction();
        try {
            if ($user = $this->create($request->all())) {
                $user = $user->load('rooms');

                UserService::moveUserToRoom($user);
            };
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $developerMsg = $e->getMessage();
            $userMsg = "Failed";

            return JsonResponse::error($developerMsg, $userMsg);
        }

        return JsonResponse::success($developerMsg, $userMsg, $user);
    }

    protected function validator(array $data)
    {
        return $validatedData = Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}