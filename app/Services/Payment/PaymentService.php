<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Repositories\PaymentRepository;

class PaymentService
{
    private PaymentRepository $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository){
        $this->paymentRepository = $paymentRepository;
    }

    public function createPayment($data){
        return $this->paymentRepository->createPayment($data);
    }

    public function getInvoices($data){
        return $this->paymentRepository->getInvoices($data);
    }
    public function invoiceByTrxId($trxId){
        return $this->paymentRepository->invoiceByTrxId($trxId);
    }

    public function sendMail($data){
        return $this->paymentRepository->paymentMailSend($data);
    }

    public function paymentSettings($data){
        return $this->paymentRepository->paymentSettings($data);
    }

    public function getFareBreakdown($data){
        return $this->paymentRepository->getFareBreakdown($data);
    }

    public function makePayment($data){
        return $this->paymentRepository->makePayment($data);
    }

}
