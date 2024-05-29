<?php

namespace App\Http\Controllers;

use App\Mail\BookingMail;
use App\Mail\ConfirmationBookingMail;
use App\Mail\PaymentConfirmationMail;
use App\Mail\PaymentMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }
    public function index(Request $request): JsonResponse
    {
        $bookings = $this->paymentService->getInvoices($request);
        return response()->json(['status' => 200, 'data' => $bookings]);
    }
    public function addPayment(Request $request)
    {
       $data =  $this->validate($request,[
            'custom_booking_id' => 'required|exists:App\Models\Booking,id',
            'amount'           => 'required',
            'payment_type'     => 'nullable',
            'payment_ref'      => 'nullable',
            'payment_details'  => 'nullable',
            'status'           => 'nullable',
            'account_name'     => 'nullable',
            'account_number'     => 'nullable',
            'bank_name'     => 'nullable',
            'branch_name'     => 'nullable',
            'routing_number'     => 'nullable'
        ]);

        $payment = $this->paymentService->createPayment($data);

        return response()->json(['status' => 200, 'message' => 'Invoice added successfully', 'invoice' => $payment]);
    }
    public function invoiceDetails($id){
        $data = $this->paymentService->invoiceByTrxId($id);
        if ($data){
            return response()->json(['status' => 200, 'message' => 'Invoice details', 'invoice' => $data]);
        }
        return response()->json(['status' => 500, 'message' => 'Invalid transaction id']);
    }
    public function updatePayment(Request $request)
    {

    }
    public function paymentMail(Request $request){
        $data =  $this->validate($request,[
            'id' => 'required|exists:App\Models\Payment,id',
        ]);
        $mailSent = $this->paymentService->sendMail($data);
        if ($mailSent){
            return response()->json(['status' => 200, 'message'=> 'Mail sent', 'data' => $mailSent]);
        }
        return response()->json(['status' => 500, 'message'=> 'Mail not sent']);
    }
    public function invoicePage(){
        return view('payment_mail');
    }

    public function paymentSuccessPage(){
        return view('payment_successful');
    }


    public function paymentSettings(Request $request){
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:App\Models\Booking,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $paymentSetting = $this->paymentService->paymentSettings($validator->getData());
        return response()->json(['status_code' => 200, 'success' => true ,'message'=>'Payment settings', 'data' => $paymentSetting]);
    }
    public function fareBreakDown(Request $request){
        $validator = Validator::make($request->all(), [
            'booking_id'   => 'required|exists:App\Models\Booking,id',
            'payment_type' => 'required|in:full,half,remaining',
            'promo_code'   => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $paymentBreakdown = $this->paymentService->getFareBreakdown($validator->getData());
        return response()->json(['status_code' => 200, 'message'=>'Payment settings', 'data' => $paymentBreakdown]);
    }

    public function makePayment($bookingId, Request $request){
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:full,half,remaining',
            'promo_code'   => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $data = $validator->getData();
        $data['booking_id'] = $bookingId;

        $url = $this->paymentService->makePayment($data);
        if ($url){
            return redirect($url);
        }
        return response()
            ->redirectTo(env('WEBSITE_URL').'rental');
    }

}
