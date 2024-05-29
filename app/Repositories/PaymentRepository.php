<?php

namespace App\Repositories;

use App\Mail\PaymentMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentBankInformation;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Repository\Baserepository\BaseRepository;
use Repository\UserRepository;

class PaymentRepository extends BaseRepository
{
    private Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    function model(): Payment
    {
        return $this->payment;
    }
    public function getInvoices($data){
        $limit = $data->get('limit',50);
        $trxId = $data->get('trxId', null);
        $bookingId = $data->get('bookingId', null);
        $paymentType = $data->get('paymentType', null);
        $status = $data->get('status', null);

        return $this->payment::query()
            ->when($trxId, function ($query) use ($trxId) {
                $query->where('transaction_id','like','%'.$trxId.'%');
            })
            ->when($bookingId, function ($query) use ($bookingId) {
                $query->where('custom_booking_id','like','%'.$bookingId.'%');
            })
            ->when($paymentType, function ($query) use ($paymentType) {
                $query->where('payment_type','like','%'.$paymentType.'%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status','like','%'.$status.'%');
            })
            ->with(['booking', 'booking.user', 'booking.bookingLocation', 'booking.selectedCarType', 'bank'])
            ->orderBy('custom_booking_id','desc')
            ->paginate($limit);
    }

    public function createPayment($data){
        $payment = new Payment();
        $payment->custom_booking_id = $data['custom_booking_id'];
        $payment->amount = $data['amount'];
        $payment->payment_type = $data['payment_type'];
        $payment->payment_ref = $data['payment_ref'];
        $payment->payment_details = $data['payment_details'];
        $payment->transaction_id = uniqid();
        $payment->status = $data['status'];
        $payment->save();
        if ($data['payment_type'] == 'bank'){
            $bankPayment = new PaymentBankInformation();
            $bankPayment->payment_id = $payment['id'];
            $bankPayment->account_name = $data['account_name'];
            $bankPayment->account_number = $data['account_number'];
            $bankPayment->bank_name = $data['bank_name'];
            $bankPayment->branch_name = $data['branch_name'];
            $bankPayment->routing_number = $data['routing_number'];
            $bankPayment->save();
        }
        return $payment;
    }
    public function invoiceByTrxId($trxId){
        return Payment::query()
            ->where('transaction_id', $trxId)
            ->with(['booking', 'booking.user', 'booking.bookingLocation', 'booking.selectedCarType'])
            ->first();

    }

    public function paymentMailSend($data){
        $payment = $this->payment->query()
            ->where('id', $data['id'])
            ->with(['booking', 'booking.user', 'booking.bookingLocation', 'booking.selectedCarType', 'bank'])
            ->first();
        $userMail = $payment['booking']['user']['email'];
        Mail::to($userMail)->send(new PaymentMail($payment));
        $payment->mail_sent = 'Sent';
        $payment->save();
        return $payment;
    }
    public function paymentSettings($data){
        $paymentOptions     = $this->availablePaymentOptions($data['booking_id']);
        $promoOptionAvailability  = $this->promoOptionAvailability($data['booking_id']);
        return ['payment_options' => $paymentOptions, 'promo_available' => $promoOptionAvailability];
    }

    public function availablePaymentOptions($bookingId){
      $completedPayments =  $this->payment
          ->query()
          ->where('custom_booking_id', $bookingId)
          ->where('status', 'Complete')
          ->get();
      if (count($completedPayments)>0){
          return [$this->payment::REMAINING_PAYMENT];
      }
      return [$this->payment::HALF_PAYMENT, $this->payment::FULL_PAYMENT];
    }

    public function promoOptionAvailability($bookingId){
        return Booking::query()
            ->where('id', $bookingId)
            ->whereNull('promo')
            ->whereDoesntHave('payments', function ($query) {
                $query->where('status', 'Complete');
            })
            ->exists();
    }

    public function getFareBreakdown($data): array
    {
        $wrongRequest = $discountAmount = $paymentAfterDiscount = $fare = $paymentAfterDiscount = $bookingPayment = $payingNow = $due = $alreadyPaid  = 0;
        $paymentOptions = $this->availablePaymentOptions($data['booking_id']);
        $promoApplicable = $this->promoOptionAvailability($data['booking_id']) ? Payment::PROMO_APPLICABLE : Payment::PROMO_INAPPLICABLE;
        $promoApplied = Payment::PROMO_NOT_APPLIED;

        $bookingData = Booking::query()
            ->with(['user'])
            ->where('id',($data['booking_id']))
            ->first();
        if ($data['promo_code'] && $promoApplicable == Payment::PROMO_APPLICABLE && $bookingData['user']['user_code']) {
            list($discountAmount, $promoApplied) = $this->discountCheck(trim($data['promo_code']), $bookingData->price, $bookingData['user']['user_code']);
        }

        if (in_array($data['payment_type'], $paymentOptions)) {
            if ($data['payment_type'] == Payment::HALF_PAYMENT) {
                list($fare,$paymentAfterDiscount, $bookingPayment, $payingNow, $due, $alreadyPaid, $requestType) =
                    $this->halfPaymentFareBreakdown($bookingData, $discountAmount);
            } elseif ($data['payment_type'] == Payment::FULL_PAYMENT) {
                list($fare, $paymentAfterDiscount,  $bookingPayment, $payingNow, $due, $alreadyPaid, $requestType) =
                    $this->fullPaymentFareBreakdown($bookingData, $discountAmount);
            } elseif ($data['payment_type'] == Payment::REMAINING_PAYMENT) {
                list($fare, $paymentAfterDiscount,  $bookingPayment, $payingNow, $due, $alreadyPaid, $requestType) =
                    $this->remainingPaymentFareBreakdown($bookingData);
            }

        } else {
            $wrongRequest = true;
            if (in_array('remaining', $paymentOptions)) {
                list($fare, $paymentAfterDiscount,  $bookingPayment, $payingNow, $due, $alreadyPaid, $requestType) =
                    $this->remainingPaymentFareBreakdown($bookingData);
            } else {
                list($fare, $paymentAfterDiscount,  $bookingPayment, $payingNow, $due, $alreadyPaid, $requestType) =
                    $this->halfPaymentFareBreakdown($bookingData, $discountAmount);
            }

        }
        return [
            'user_code' => $bookingData['user']['user_code'] ?? null,
            'fare' => $fare,
            'discount_amount' => number_format($discountAmount),
            'discounted_payment' => $paymentAfterDiscount,
            'paying_now' => $payingNow,
            'booking_payment' => $bookingPayment,
            'due' => $due,
            'already_paid' => $alreadyPaid,
            'request_type' => $requestType ?? '',
            'promo_applicable' => $promoApplicable,
            'promo_applied' => $promoApplied,
            'promo_code' => $promoApplied == $this->payment::PROMO_APPLIED ? $data['promo_code'] : null,
            'wrong_request' => $wrongRequest
        ];

    }

    public function halfPaymentFareBreakdown($bookingData, $discountAmount ): array
    {
        $paymentSetting = PaymentSetting::query()->first();
        $fare = $bookingData['price'];
        $paymentAfterDiscount = $fare - $discountAmount;
        $bookingPayment   = round(($paymentSetting['minimum_pay_percentage']/100) * $paymentAfterDiscount) ;
        $payingNow =  round($bookingPayment);
        $due         = round($paymentAfterDiscount- $bookingPayment) ;
        $alreadyPaid = 0;
        $requestType = $this->payment::HALF_PAYMENT;

        return array(
            number_format($fare),
            number_format($paymentAfterDiscount),
            number_format($bookingPayment),
            number_format($payingNow),
            number_format($due),
            number_format($alreadyPaid),
            $requestType
        );
    }

    public function fullPaymentFareBreakdown($bookingData, $discountAmount ): array
    {
        $fare = $bookingData['price'];
        $paymentAfterDiscount = $fare - $discountAmount;
        $bookingPayment   = round($paymentAfterDiscount) ;
        $payingNow =  round($paymentAfterDiscount);
        $due         = round($paymentAfterDiscount - $bookingPayment) ;
        $alreadyPaid = 0;
        $requestType = $this->payment::FULL_PAYMENT;
        return  array(
            number_format($fare),
            number_format($paymentAfterDiscount),
            number_format($bookingPayment),
            number_format($payingNow),
            number_format($due),
            number_format($alreadyPaid),
            $requestType
        );
    }

    public function remainingPaymentFareBreakdown($bookingData): array
    {
        $fare = $bookingData['price'];
        $paymentAfterDiscount = $fare - $bookingData['discount_amount'];
        $alreadyPaid = $bookingData['paid_amount'];
        $bookingPayment = $bookingData['paid_amount'];
        $payingNow =  round($paymentAfterDiscount - $alreadyPaid);
        $due         = 0;
        $requestType = $this->payment::REMAINING_PAYMENT;

        return  array(
            number_format($fare),
            number_format($paymentAfterDiscount),
            number_format($bookingPayment),
            number_format($payingNow),
            number_format($due),
            number_format($alreadyPaid),
            $requestType
        );

    }

    public function discountCheck($promoCode , $bookingFare, $userCode): array
    {
        $discountOnFare = 0;
        $serviceUrl = env('PROMO_SERVICE_URL');
        $adminInfo = $this->getAdminInformation();

        $promoResponse = Http::withHeaders([
            'Authorization' => $adminInfo['token'],
        ])->post($serviceUrl . '/v1/promos/users/apply', [
            'user_code'    => $userCode,
            'promo_code'   => $promoCode,
            'service_type' => 'rental',
        ])->json();
        if ($promoResponse['success']){
            $promoInfo = $promoResponse['data'];
            $promoType = $promoInfo['type']['identifier'] == 1 ? 'flat' : 'percentage';
            $maxDiscount = $promoInfo['max_discount'];
            $value = $promoInfo['value'];
            if ($promoType == 'flat'){
                $discountOnFare = $maxDiscount;
            }else{
                $discountBasedOnBookingFare = round($bookingFare * ($value/100));
                $discountOnFare = min($discountBasedOnBookingFare, $maxDiscount);
            }
            return array($discountOnFare, $this->payment::PROMO_APPLIED);
        }
        return array($discountOnFare, $this->payment::PROMO_INVALID);
    }

    public function makePayment($data){
        $paymentOptions =  $this->availablePaymentOptions($data['booking_id']);
        $bookingData = Booking::query()
            ->with(['user'])
            ->where('id',($data['booking_id']))
            ->first();
        if ($bookingData['payment_status'] == 'fully_paid'){
            return false;
        }
        if (!in_array($data['payment_type'], $paymentOptions)){
            return false;
        }
        $fareBreakDown = $this->getFareBreakdown($data);
        if ($fareBreakDown['paying_now'] < 1){
            return false;
        }
        $paymentData['transaction_id'] = uniqid();
        $paymentData['custom_booking_id'] = $bookingData['id'];
        $paymentData['amount'] = (int) str_replace(',', '', $fareBreakDown['paying_now']);
        $paymentData['payment_type'] = $this->payment::ONLINE_PAYMENT;
        $paymentData['payment_ref'] = '';
        $paymentData['payment_details'] = 'payment for rental booking';
        $paymentData['mail_sent'] = 'Not Sent';
        $paymentData['status'] = 'Pending';
        $paymentData['promo'] = $fareBreakDown['promo_code'];
        $paymentData['discount_amount'] = $fareBreakDown['discount_amount'];
        $payment = $this->createPayment($paymentData);
        $url = route('makePayment.store', [
            'transaction_id' => $payment['transaction_id'],
            'promo_code' => $paymentData['promo'],
            'discount_amount' => $paymentData['discount_amount']
        ]);
        return $url;
    }

    public function  getAdminInformation(){
        $userApiUrl =  env('USER_SERVICE_URL').'auth';
        return Http::withHeaders([
            'Authorization' => request()->header('authorization')
        ])->post($userApiUrl, [
            'strategy' => 'MobileAndPassword',
            'mobile' => '01684323538',
            'secret' => '1234shuttle5678',
            'system' => 'Cockpit',
            'role' => 'Internal User',
        ])->json('data');
    }

}
