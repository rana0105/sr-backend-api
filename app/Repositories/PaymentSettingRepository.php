<?php

namespace App\Repositories;

use App\Models\PaymentSetting;
use Repository\Baserepository\BaseRepository;

class PaymentSettingRepository extends BaseRepository
{

    private PaymentSetting $paymentSetting;

    public function __construct(PaymentSetting $paymentSetting)
    {
        $this->paymentSetting = $paymentSetting;
    }

    function model(): PaymentSetting
    {
        return $this->paymentSetting;
    }

}
