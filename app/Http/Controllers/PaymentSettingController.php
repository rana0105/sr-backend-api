<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentSettingController extends Controller
{
    private PaymentSettingService $paymentSettingService;

    public function __construct(PaymentSettingService $paymentSettingService){
        $this->paymentSettingService = $paymentSettingService;
    }


    public function index()
    {
        $paymentSettings = $this->paymentSettingService->getPaymentSetting();
        return response()->json(['data' => $paymentSettings]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'minimum_pay_percentage' => 'required|numeric|between:10,95',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $paymentSetting = $this->paymentSettingService->createPaymentSetting($validator->getData());
        if ($paymentSetting){
            return response()->json(['message' => 'Payment setting created', 'success' => true, 'data' => $paymentSetting, 'status_code' => 200]);
        }
        return response()->json(['message' => 'Payment setting creation failed', 'success' => false, 'status_code' => 400]);

    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'minimum_pay_percentage' => 'required|numeric|between:10,95',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $paymentSetting = $this->paymentSettingService->paymentSettingUpdate($id, $validator->getData());
        if ($paymentSetting){
            return response()->json(['message' => 'Payment setting updated', 'success' => true, 'data' => $paymentSetting, 'status_code' => 200]);
        }
        return response()->json(['message' => 'Payment setting update failed', 'success' => false, 'status_code' => 400]);
    }

}
