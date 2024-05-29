<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationBookingMail;
use App\Models\Booking;
use App\Models\CustomBooking;
use App\Models\MailSettings;
use App\Models\Payment;
use App\Repositories\MailSettingsRepository;
use App\Repositories\PaymentRepository;
use App\Services\MessageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class SslCommerzPaymentController extends Controller
{

    public function customPayment(Request $request)
    {
        $validatedData =  $this->validate($request,[
            'transaction_id'  => 'required|exists:App\Models\Payment,transaction_id',
        ]);

        $validatedData['promo_code'] = $request->get('promo_code', null);
        $validatedData['discount_amount'] = $request->get('discount_amount', 0);

        $payment = Payment::where('transaction_id',$validatedData['transaction_id'])->first();

        if(is_null($payment)) {
            abort(404);
        }

        $book = Booking::where('id',$payment['custom_booking_id'])
            ->with(['user', 'bookingLocation', 'selectedCarType'])
            ->first();

        if(is_null($book)) {
            abort(404);
        }
        if ($book->payment_status == Booking::PAYMENT_UNVERIFIED){
            $book->payment_status = Booking::UNPAID;
            $book->save();
        }
        $post_data                 = [];
        $post_data['total_amount'] = $payment->amount; # You cant not pay less than 10
        $post_data['currency']     = "BDT";
        $post_data['tran_id']      = $payment->transaction_id; // tran_id must be unique
        $post_data['booking_id']   = $book->id;
        $post_data['booking_price'] = $book->price;

        # CUSTOMER INFORMATION
        $post_data['cus_name']     = $book->user->name;
        $post_data['cus_email']    = $book->user->email;
        $post_data['cus_add1']     = 'User Address';
        $post_data['cus_add2']     = "";
        $post_data['cus_city']     = "";
        $post_data['cus_state']    = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country']  = "Bangladesh";
        $post_data['cus_phone']    = $book->user->phone;
        $post_data['cus_fax']      = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name']     = "Store Test";
        $post_data['ship_add1']     = "Dhaka";
        $post_data['ship_add2']     = "Dhaka";
        $post_data['ship_city']     = "Dhaka";
        $post_data['ship_state']    = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone']    = "";
        $post_data['ship_country']  = "Bangladesh";

        $post_data['shipping_method']  = "NO";
//        $post_data['product_name']     = $book->package_type;
        $post_data['product_name']     = "Full Package";
        $post_data['product_category'] = $book->way_type == 1 ? 'One Way' : 'Both Way' ;
        $post_data['product_profile']  = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $book->user->phone;
        $post_data['value_b'] = "custom";
        $post_data['value_c'] = $validatedData['promo_code'];
        $post_data['value_d'] = $validatedData['discount_amount'];
        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        }

    }

    public function success(Request $request)
    {
        $tran_id  = $request->input('tran_id');
        $amount   = $request->input('amount');
        $currency = $request->input('currency');
        $phone    = $request->input('value_a');
        $package  = $request->input('value_b');
        $promo_code = $request->input('value_c') ?? null;
        $discount = (int) $request->input('value_d') ?? 0;
        $sslc = new SslCommerzNotification();

        $order_detials = Payment::where('transaction_id',$tran_id)->first();

        if ($order_detials->status == 'Pending') {

            if (env('IS_LOCALHOST')){
                $validation = true;
            }else{
                $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());
            }

            if ($validation) {
                $book = Booking::where('id', $order_detials->custom_booking_id)
                    ->with(['user', 'bookingLocation', 'selectedCarType', 'payments'])
                    ->first();

                $bookingFare = $book->price;
                $already_paid = $book->paid_amount;
                $new_amount = $amount + $already_paid;
                $paid_at = Carbon::now()->format('Y-m-d H:i:s');

                $totalAmount = $new_amount + max($book['discount_amount'], $discount);
                $payment_status = $totalAmount >= $bookingFare ? Booking::FULLY_PAID : Booking::PARTIALLY_PAID;

                $update_data = [
                    'paid_amount' => $new_amount,
                    'payment_status' => $payment_status
                ];

                if ($already_paid < 1) {
                    $update_data['status'] = Booking::CONFIRMED;
                    $update_data['promo'] = $promo_code;
                    $update_data['discount_amount'] = $discount;
                }

                $book->update($update_data);

                app(MessageService::class)->sendCodeSms($phone, $book, $amount);

                $order_detials->status = 'Complete';
                $order_detials->paid_at = $paid_at;
                $order_detials->card_type = $request->input('card_type') ?? null;
                $order_detials->card_no = $request->input('card_no') ?? null;
                $order_detials->card_issuer = $request->input('card_issuer') ?? null;
                $order_detials->bank_tran_id = $request->input('bank_tran_id') ?? null;
                $order_detials->save();

                $mailSettings = (new MailSettingsRepository(new MailSettings()))->mailSettingByType('booking_mail_admin');
                $details = $book;
                $details['transaction_id'] = $order_detials['transaction_id'];
                $details['paying_now'] = $order_detials['amount'];
                $details['remaining_due'] = $bookingFare - ($book['paid_amount'] + $book['discount_amount']);
                $details['already_paid']  = $already_paid;
                $requestType = 'HTML_EMAIL';

                try {
                    if ($already_paid < 1 && $promo_code) {
                        $serviceUrl = env('PROMO_SERVICE_URL');
                        $adminInfo = (new PaymentRepository(new Payment()))->getAdminInformation();
                        Http::withHeaders([
                            'Authorization' => $adminInfo['token'],
                        ])->post($serviceUrl . '/v1/promos/users/apply/confirmed', [
                            'user_code'    => $book->user->user_code,
                            'promo_code'   => $promo_code,
                        ])->json();
                    }

                    $date = Carbon::now()->format('d/M/Y');
                    $time = Carbon::now()->format('g:i A');
                    $subject = $already_paid < 1 ? 'Rental Booking & Payment Confirmation at ' : 'Rental Payment Confirmation at ';
                    $subject .= $date . ' ' . $time;

                    $customerEmail['subject'] = $subject;
                    $customerEmail['to'] = [$book->user->email];
                    $customerEmail['cc'] = [];
                    $customerEmail['user_type'] = "Customer";

                    $opsTeamEmail['subject'] = $subject;
                    $opsTeamEmail['to'] = [$mailSettings['to']];
                    $opsTeamEmail['cc'] = $mailSettings['cc'] ?? [];
                    $opsTeamEmail['user_type'] = "Customer";

                    if ($already_paid < 1) {
                        $customerEmail['email_body'] = View::make('cus_first_payment_mail', compact('details'))->render();
                        $opsTeamEmail['email_body'] = View::make('ops_team_first_payment_mail', compact('details'))->render();
                    } else {
                        $customerEmail['email_body'] = View::make('customer_last_payment_mail', compact('details'))->render();
                        $opsTeamEmail['email_body'] = View::make('ops_team_last_payment_mail', compact('details'))->render();
                    }

                    (new MailSettingsRepository(new MailSettings()))->sendHtmlMail($requestType, array($customerEmail, $opsTeamEmail));

                } catch (\Throwable $exception) {

                }

                return redirect()->route('paymentSuccessPage');
            }
            else {

                DB::table('payments')
                  ->where('transaction_id', $tran_id)
                  ->update(['status' => 'Pending']);


                echo "validation Fail";
//                return redirect()->route('notify')->with('fail', 'validation Fail');
            }
        }

        elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {

//            $book = Booking::where('id',$booking_id)->first();
//
//            app(MessageService::class)->sendCodeSms($phone, $book, $amount);
            echo  "Your Transaction is successfully Completed";
//            return redirect()->route('notify')->with('success', 'Your Transaction is successfully Completed');
        }

        else {
            echo  "Invalid Transaction";
//            return redirect()->route('notify')->with('fail', 'Invalid Transaction');
        }

    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = Payment::where('transaction_id',$tran_id)->first();

        if ($order_detials->status == 'Pending') {

            DB::table('payments')
              ->where('transaction_id', $tran_id)
              ->update(['status' => 'Pending']);

            //echo "Transaction is Falied";
            return redirect()->route('notify')->with('fail', 'Transaction is Falied');
        } elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
//            return redirect()->route('notify')->with('success', 'Your Transaction is successfully Completed');
        } else {
            echo  "Invalid Transaction";
//            return redirect()->route('notify')->with('fail', 'Invalid Transaction');
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = Payment::where('transaction_id',$tran_id)->first();

        if ($order_detials->status == 'Pending') {

            DB::table('payments')
              ->where('transaction_id', $tran_id)
              ->update(['status' => 'Pending']);

            //echo "Transaction is Cancel";
            return redirect()->route('notify')->with('fail', 'Transaction is Falied');
        } elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Your Transaction is successfully Completed";
//            return redirect()->route('notify')->with('success', 'Your Transaction is successfully Completed');
        } else {
            echo "Transaction  Invalid";
//            return redirect()->route('notify')->with('fail', 'Invalid Transaction');
        }

    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');
            $amount   = $request->input('amount');
            $phone   = $request->input('value_a');
            $package = $request->input('value_b');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = Payment::where('transaction_id',$tran_id)->first();

            if ($order_details->status == 'Pending') {
                $sslc       = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency,
                    $request->all());
                if ($validation == true) {

//                    $book = Booking::where('id',$booking_id)->first();
//                    $update_product = DB::table('bookings')
//                                        ->where('id', $booking_id)
//                                        ->update(['status' => 'Processing','transaction_id'=>uniqid()]);
//                    $already_paid = $book->paid_amount;
//                    $new_amount = $amount + $already_paid;
//                    $update_product = DB::table('bookings')
//                        ->where('id', $booking_id)
//                        ->update(['paid_amount' => $new_amount]);

                    DB::table('payments')
                      ->where('transaction_id', $tran_id)
                      ->update(['status' => 'Processing']);

//                    app(MessageService::class)->sendCodeSms($phone, $book, $amount);
                } else {

                    DB::table('payments')
                      ->where('transaction_id', $tran_id)
                      ->update(['status' => 'Pending']);


                    echo "validation Fail";
                }

            } elseif ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
//
//                $book = Booking::where('id',$booking_id)->first();
//
//                app(MessageService::class)->sendCodeSms($phone, $book, $amount);
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
