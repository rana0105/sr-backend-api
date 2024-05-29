<?php

namespace App\Http\Controllers\Authenticaion;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\JsonResponseTrait;
use App\Http\Requests\Auth\UserStoreRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use JsonResponseTrait;

    public function register(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $userInformation = ['user' => $user, 'token' => $user->createToken("SHUTTLE")->plainTextToken];
            return $this->json('User Created Successfully', $userInformation);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['phone', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('phone', $request->phone)->first();
            $permissions = [];
            foreach ($user->permissions as  $value) {
                array_push($permissions, $value->name);
            }
            $userInformation = ['user' => $user, 'token' => $user->createToken("SHUTTLE")->plainTextToken, 'permissions' => $permissions];
            return $this->json('User Logged In Successfully', $userInformation);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $users = User::all();
        if (!$users) {
            return $this->bad('Something Wrong to Get User Lists!');
        }
        return $this->json('Show User List', $users);
    }
}
