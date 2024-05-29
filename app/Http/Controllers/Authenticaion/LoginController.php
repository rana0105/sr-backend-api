<?php

namespace App\Http\Controllers\Authenticaion;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Login\LoginService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(Request $request)
    {
        return $this->loginService->loginUser($request);
    }

    public function otpForm()
    {
        return view('otp-form-v2');
    }

    public function requestOtp(Request $request)
    {
        $phone = $request->phone_number;
        if ($phone){
            $this->loginService->requestOtp($phone);
            return view('customer-login-v2', compact('phone'));
        }else{
            return view('otp-form-v2');
        }

    }

    public function submitOtp(Request $request)
    {
        $authentication = $this->loginService->loginWithOtp($request);

        if ($authentication['success']) {
            $token = base64_encode($authentication['data']['token']);
            $newUser = $authentication['data']['new-user'];
            $userInfo = $this->loginService->profileData($authentication['data']['token']);
            $userExistsInRental = $this->loginService->getUserInfo($userInfo);
            if ($userExistsInRental){
                return response()
                    ->redirectTo(env('WEBSITE_URL') . 'rental?new_user=' . $newUser . '&token=' . $token);
            }
            return response()
                ->redirectTo(env('WEBSITE_URL') . 'create-account?new_user=' . $newUser . '&token=' . $token);
        }
        return response()
            ->redirectTo(env('WEBSITE_URL') . 'rental?new_user=false&token=false');

    }

    public function customerProfile(): JsonResponse
    {
        $jwtToken = request()->header('Authorization');
        $profileData = $this->loginService->profileData($jwtToken);
        if ($profileData) {
            return response()->json(['status' => 200, 'data' => $profileData]);
        }
        return response()->json(['status' => 404, 'data' => 'Profile not found']);

    }

    public function profileUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(request()->user()->mobile, 'phone'),
            ],
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $data = $validator->getData();

        if ($this->loginService->updateProfileInRentalService($data)) {
            $updateProfile = $this->loginService->profileUpdate($data);
            $profileData = $this->loginService->profileData(request()->header('Authorization'));
            if ($profileData && $updateProfile) {
                return response()->json(['status' => 200, 'data' => $profileData, 'message' => 'Profile updated successfully']);
            }
        }
        return response()->json(['status' => 400, 'message' => 'Profile update unsuccessful']);
    }

    public function referralCode()
    {
       $referralCode = $this->loginService->getReferralCode();
       if ($referralCode['referral_code']){
           return response()->json(['status' => 200, 'data' => $referralCode['referral_code'], 'message' => 'Customer referral code']);
       }
        return response()->json(['status' => 400, 'message' => 'Customer referral code not found']);
    }


}
