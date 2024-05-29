<?php

namespace App\Services\Login;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Mockery\Exception;
use Repository\UserRepository;
use Illuminate\Support\Facades\Http;
use Shuttle\PhpPack\Traits\ShuttlePhpPackTrait;
use Shuttle\PhpPack\Classes\Responses\ApiSuccessResponse;
use Shuttle\PhpPack\Classes\Exceptions\ApiBadRequestException;

class LoginService
{
    use ShuttlePhpPackTrait;

    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function loginUser($request)
    {
        $responseData = $this->getLoginResponse($request);
        $getStatusCode = $responseData->getStatusCode();

        $statusCodes = [Response::HTTP_UNAUTHORIZED, Response::HTTP_NOT_FOUND, Response::HTTP_UNPROCESSABLE_ENTITY, Response::HTTP_BAD_REQUEST];
        if (in_array($getStatusCode, $statusCodes)) {
            throw new ApiBadRequestException($responseData['message']);
        }

        $data = json_decode($responseData, true);
        $token = $data['data']['token'];
        $decode = $this->tokenVerify($token);
        $userCode = $decode['user']->user_code;
        $name = $decode['user']->name;

        if (!$token) {
            throw new ApiBadRequestException($responseData['message']);
        }

        $user = $this->existingUser($userCode, $name);
        if (!$user) {
            throw new ApiBadRequestException($responseData['message']);
        }
        $permissions = [];
        foreach ($user->permissions as  $value) {
            array_push($permissions, $value->name);
        }
        $response = ['user' => $user, 'token' => $token, 'permissions' => $permissions];
        return (new ApiSuccessResponse('User login successful!', $response))->getPayload();
    }

    private function getLoginResponse($request)
    {
        $strategy   = 'MobileAndPassword';
        $mobile     = $request['phone'];
        $secret   = $request['password'];
        $serviceUrl = env('USER_SERVICE_URL');
        return Http::withToken('token')->post($serviceUrl . 'auth', [
            'strategy' => $strategy,
            'mobile' => $mobile,
            'secret' => $secret,
            'role' => 'Internal User',
            'system' => 'Cockpit',
        ]);
    }

    private function existingUser($userCode, $name)
    {
        $existingUser = $this->userRepo->userPermissions($userCode);
        if ($existingUser) {
            $existingUser->setAttribute('name', $name);
        }
        return $existingUser;
    }

    public function requestOtp($phone): void
    {
        $serviceUrl = env('USER_SERVICE_URL');
        Http::get($serviceUrl . 'send-otp', [
            'mobile' => $phone,
        ]);
    }

    public function loginWithOtp($request){
        $phone = $request->phone;
        $otp = $request->otp;
        $serviceUrl = env('USER_SERVICE_URL');

        return Http::withToken('token')->post($serviceUrl . 'auth', [
            'strategy' => 'MobileAndOtp',
            'mobile' => $phone,
            'secret' => $otp,
        ])->json();
    }

    public function profileUpdate($data){
       return Http::withHeaders([
            'Authorization' => request()->header('Authorization')
        ])->post(env('USER_SERVICE_URL') . 'v1/users/profile', [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
        ])->json('data');
    }

    public function profileData($token){
        try {
            return  Http::withHeaders([
                'Authorization' => $token
            ])->get(env('USER_SERVICE_URL') . 'v1/users/profile')->json('data');
        }catch (Exception $exception){
        }
    }
    public function updateProfileInRentalService($profileData): bool
    {
      return  $this->userRepo->updateProfileInRentalService($profileData);
    }
    public function adminToken(){
        return $this->userRepo->getAdminInformation();
    }
    public function getReferralCode(){
        return $this->userRepo->getReferralCode();
    }

    public function getUserInfo($userInfo){
        return $this->userRepo->getUserInfo($userInfo);
    }

}
