<?php

namespace App\Services\Payment;

use App\Repositories\PaymentSettingRepository;

class PaymentSettingService
{
    private PaymentSettingRepository $paymentSettingRepository;

    public function __construct(PaymentSettingRepository $paymentSettingRepository){
        $this->paymentSettingRepository = $paymentSettingRepository;
    }

    public function getPaymentSetting(){
        return $this->paymentSettingRepository->getAll();
    }

    public function createPaymentSetting($data){
        $paymentSettingsCount = $this->paymentSettingRepository->getAll()->count();
        if ($paymentSettingsCount<1){
            $data['created_by'] = request()->user()->user_code;
            return $this->paymentSettingRepository->create($data);
        }
        return false;
    }

    public function paymentSettingUpdate($id, $data){
        $data['updated_by'] = request()->user()->user_code;
        return $this->paymentSettingRepository->updateByID($id, $data);
    }
}
