<?php

namespace App\Repositories;

use App\Http\Resources\BookingResource;
use App\Mail\BookingMail;
use App\Mail\CustomerBookingNotificationMail;
use App\Mail\PaymentMail;
use App\Mail\RefundRequestMail;
use App\Mail\RefundRequestMailToCustomer;
use App\Models\Booking;
use App\Models\BookingLocation;
use App\Models\CarTypeWisePrice;
use App\Models\MailSettings;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Repository\Baserepository\BaseRepository;
use Repository\CarTypeWisePriceRepository;
use Repository\UserRepository;

class BookingRepository extends BaseRepository
{
    private Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function model(): Booking
    {
        return $this->booking;
    }

    public function getBookings(Request $data)
    {
        $limit = $data->get('limit', 50);
        return $this->bookingQuery($data)->paginate($limit);
    }

    public function userUpdate()
    {
        $user = User::query()
            ->where('phone', request()->user()->mobile)
            ->first();
        if ($user){
            $user->name = request()->user()->name;
            $user->email = request()->user()->email;
            $user->user_code = request()->user()->user_code;
            $user->save();
            return $user;
        }else{
            $newUser = new User();
            $newUser->name = request()->user()->name;
            $newUser->email = request()->user()->email;
            $newUser->phone = request()->user()->mobile;
            $newUser->user_code = request()->user()->user_code;
            $role = Role::query()->where('name', 'customer')->first();
            if ($role) {
                $newUser->role_id = $role['id'];
                $newUser->save();
                return $newUser;
            }
            $newUser->save();
            return $newUser;
        }
    }

    public function customerTripHistory(Request $data)
    {
        $data['customerCode'] = request()->user()->user_code;
        $data['customerTripType'] = $data->get('customerTripType', null);
        try {
            $this->userUpdate();
        } catch (\Throwable $exception) {
        }
        $limit = $data->get('limit', 100);
        return $this->bookingQuery($data)->paginate($limit);
    }

    public function customerTripReorder($data)
    {
        $oldBooking = $this->getBookingById($data['booking_id']);

        $newBookingUser = request()->user()->user_code;

        $oldBookingUser = $oldBooking['user']['user_code'];

        if ($newBookingUser != $oldBookingUser) {
            return false;
        }

        $newBooking = new Booking();

        $newBooking->user_id = $oldBooking['user_id'];
        $newBooking->total_distance = $oldBooking['total_distance'];
        $newBooking->package_id = $oldBooking['package_id'];
        $newBooking->way_type = $oldBooking['way_type'];
        $newBooking->car_type = $oldBooking['car_type'];
        $newBooking->price = $oldBooking['price'];
        $newBooking->payment_status = Booking::PAYMENT_UNVERIFIED;
        $newBooking->status = Booking::PENDING;
        $newBooking->pickup_date_time = $data['pickup_date_time'];
        $newBooking->return_date_time = $data['return_date_time'];

        $newBooking->save();
        $locationInfos = $oldBooking['bookingLocation']->toArray();
        foreach ($locationInfos as $locationData) {
            $bookingLocation = new BookingLocation();
            $this->saveLocationInfo($bookingLocation, $newBooking, $locationData);
        }
//        $this->sendMailAfterBooking($newBooking);

        return $newBooking;
    }

    public function getBookingsForExcel(Request $data)
    {
        return $this->bookingQuery($data)
            ->get()
            ->map(function ($collection) {
                return [
                    'booking_id' => 'SRB-' . $collection->id,
                    'booked_at' => Carbon::parse($collection->created_at)->format('d-M-Y h:i a'),
                    'name' => $collection->user->name,
                    'phone' => $collection->user->phone,
                    'email' => $collection->user->email,
                    'way_type' => $collection->way_type == 2 ? 'Round Trip' : 'One Way',
                    'price' => number_format($collection->price),
                    'paid_amount' => number_format($collection->paid_amount),
                    'due' => number_format($collection->price - $collection->paid_amount),
                    'status' => $collection->status,
                    'car_type' => $collection->selectedCarType->car_type_name,
                    'driver_phone' => $collection->driver_phone,
                    'pickup_date_time' => Carbon::parse($collection->pickup_date_time)->format('d-M-Y h:i a'),
                    'return_date_time' => Carbon::parse($collection->return_date_time)->format('d-M-Y h:i a'),
                    'pickup_address' => $collection->bookingLocation[0]->address,
                    'dropoff_address' => $collection->bookingLocation[1]->address,
                    'remarks' => $collection->remarks
                ];
            });
    }

    public function bookingQuery(Request $data)
    {
        $userPhone = $data->get('userPhone', null);
        $userEmail = $data->get('userEmail', null);
        $bookingId = $data->get('bookingId', null);
        $bookedFrom = $data->get('bookedFrom', null);
        $bookedTill = $data->get('bookedTill', null);
        $payStatus = $data->get('payStatus', null);
        $tripStatus = $data->get('tripStatus', null);
        $customerCode = $data->get('customerCode', null);
        $wayType = $data->get('wayType', null);
        $customerTripType = $data->get('customerTripType', null);

        return $this->booking::query()
            ->where('payment_status', '!=', $this->booking::PAYMENT_UNVERIFIED)
            ->with([
                'user', 'bookingLocation', 'selectedCarType',
                'payments' => function ($query) use ($customerCode) {
                    return $query->when($customerCode, function ($query) {
                        $query->where('status', 'Complete');
                    });
                },
            ])
            ->when($customerCode, function ($query) use ($customerCode, $customerTripType) {
                $query->whereHas('user', function ($q) use ($customerCode) {
                    $q->where('user_code', $customerCode);
                });
            })
            ->when($customerTripType, function ($query) use ($customerTripType) {
                $query->where('status', $customerTripType);
            })
            ->when($bookedFrom, function ($query) use ($bookedFrom) {
                $query->where('created_at', '>=', $bookedFrom);
            })
            ->when($bookedTill, function ($query) use ($bookedTill) {
                $query->where('created_at', '<', Carbon::parse($bookedTill)->addDays(1)->format('Y-m-d'));
            })
            ->when($bookingId, function ($query) use ($bookingId) {
                $query->where('id', 'like', $bookingId . '%');
            })
            ->when($payStatus, function ($query) use ($payStatus) {
                $query->where('payment_status', $payStatus);
            })
            ->when($tripStatus, function ($query) use ($tripStatus) {
                $query->where('status', $tripStatus);
            })
            ->when($wayType, function ($query) use ($wayType) {
                $query->where('way_type', $wayType);
            })
            ->when($userPhone, function ($query) use ($userPhone) {
                $query->whereHas('user', function ($q) use ($userPhone) {
                    $q->where('phone', 'like', '%' . $userPhone . '%');
                });
            })
            ->when($userEmail, function ($query) use ($userEmail) {
                $query->whereHas('user', function ($q) use ($userEmail) {
                    $q->where('email', 'like', '%' . $userEmail . '%');
                });
            })
            ->orderBy('id', 'desc');
    }

    public function bookingDataForMarketingTeam(Request $data)
    {
        $userPhone = $data->get('phone', null);
        $bookingId = $data->get('id', null);
        if ($bookingId) {
            $bookingId = trim($bookingId, 'SRBsrb- ');
        }
        return $this->booking::query()
            ->with(['user', 'bookingLocation'])
            ->when($bookingId, function ($query) use ($bookingId) {
                $query->where('id', $bookingId);
            })
            ->when($userPhone, function ($query) use ($userPhone) {
                $query->whereHas('user', function ($q) use ($userPhone) {
                    $q->where('phone', $userPhone);
                });
            })
            ->get()
            ->map(function ($collection) {
                $name = $collection->user->name;
                $nameArray = explode(' ', $name);
                $lastName = array_pop($nameArray);
                $firstName = implode(' ', $nameArray);
                return [
                    'booking_id' => 'SRB-' . $collection->id,
                    'booking_date' => Carbon::parse($collection->created_at)->format('d-M-Y h:i a'),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $collection->user->email,
                    'status' => $collection->status,
                    'phone' => $collection->user->phone,
                    'pickup_address' => $collection->bookingLocation[0]->address,
                    'dropoff_address' => $collection->bookingLocation[1]->address
                ];
            });

    }

    public function getBookingById($id)
    {
        return Booking::query()
            ->with(['user', 'bookingLocation', 'selectedCarType', 'payments'])
            ->where('id', $id)
            ->first();
    }

    public function sendDriverMail($booking){
        $email['subject'] = "Driver Details is now available for your Trip";
        $email['email_body'] = View::make('car_and_driver_details', compact('booking'))->render() ;
        $email['to'] = array($booking->user->email);
        $email['cc'] = [];
        $email['user_type'] = "Customer";
        $requestType = 'HTML_EMAIL';
        (new MailSettingsRepository(new MailSettings()))->sendHtmlMail($requestType, array($email));
    }

    public function createBookingRequest($data)
    {
        try {
            $user = $this->userUpdate();
        } catch (\Throwable $exception) {
            return $this->model()::USER_UPSERT_FAILED;
        }

        $data['user_id'] = $user->id;
        $booking = new Booking();
        $bookingData = $this->saveBookingData($booking, $data);
        if (!$bookingData){
            return false;
        }
        foreach ($data['location_infos'] as $locationData) {
            $bookingLocation = new BookingLocation();
            $this->saveLocationInfo($bookingLocation, $bookingData, $locationData);
        }
        return $bookingData;
    }

    public function sendMailAfterBooking($bookingData)
    {

    }
    public function getMailSettingsByType($type){
      return (new MailSettingsRepository(new MailSettings()))->mailSettingByType($type);
    }

    public function sendPayLetterMail($bookingData){

        $details = $this->getBookingById($bookingData['id']);
        $mailSettings = $this->getMailSettingsByType('booking_mail_admin');

        $details['mail_body_ops_team'] = View::make('pay_letter_mail_ops_team', compact('details'))->render();
        $details['mail_body_customer'] = View::make('pay_letter_mail_customer', compact('details'))->render();
        $requestType = 'HTML_EMAIL';

        $date = Carbon::parse($details['created_at'])->format('d/M/Y');
        $time = Carbon::parse($details['created_at'])->format('g:i A');
        $subjectEmailOpsTeam = 'Rental Pending Booking Request On '.$date.' '.$time;

        $emailOpsTeam['subject'] = $subjectEmailOpsTeam;
        $emailOpsTeam['email_body'] = $details['mail_body_ops_team'];
        $emailOpsTeam['to'] = array($mailSettings['to']);
        $emailOpsTeam['cc'] = $mailSettings['cc'] ?? [];
        $emailOpsTeam['user_type'] = "Customer";

        $emailCustomer['subject'] = "Rental Booking Request is Pending";
        $emailCustomer['email_body'] = $details['mail_body_customer'];
        $emailCustomer['to'] = array($details->user->email);
        $emailCustomer['cc'] = [];
        $emailCustomer['user_type'] = "Customer";

        (new MailSettingsRepository(new MailSettings()))->sendHtmlMail($requestType, array($emailOpsTeam, $emailCustomer));
    }

    public function findUser($phoneNumber)
    {
        return User::query()->where('phone', $phoneNumber)->first();
    }

    public function insertUser($data)
    {
        return User::create(['name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email']]);
    }

    public function fetchBookingPrice($data){
        $priceFetch['pickupPlaceId']    = $data['location_infos'][0]['place_id'];
        $priceFetch['dropoffPlaceId']   = $data['location_infos'][1]['place_id'];
        $priceFetch['way_type']         = $data['way_type'];
        $priceFetch['pickup_date_time'] = $data['pickup_date_time'];
        $priceFetch['return_date_time'] = $data['return_date_time'];
        list($prices, $distance) =(new CarTypeWisePriceRepository(new CarTypeWisePrice(), new GoogleApiRepository()))
            ->tripPrices($priceFetch);
        return collect($prices)->where('id', $data['car_type'])->first()['approx_fare'];
    }

    public function saveBookingData($booking, $data)
    {
        try {
            $price = $this->fetchBookingPrice($data);
        }catch (\Exception $exception){
            return  false;
        }
        $booking->user_id = $data['user_id'];
        $booking->total_distance = $data['total_distance'];
        $booking->package_id = $data['package_id'];
        $booking->way_type = $data['way_type'];
        $booking->car_type = $data['car_type'];
        $booking->price = $price;
        $booking->payment_status = $data['payment_status'];
        $booking->status = Booking::PENDING;
        $booking->pickup_date_time = $data['pickup_date_time'];
        $booking->return_date_time = $data['return_date_time'];
        $booking->save();
        return $booking;
    }

    public function saveLocationInfo($bookingLocation, $bookingData, $locationData)
    {
        $bookingLocation->booking_id = $bookingData['id'];
        $bookingLocation->address = $locationData['address'];
        $bookingLocation->lat = $locationData['lat'];
        $bookingLocation->lng = $locationData['lng'];
        $bookingLocation->save();
    }

    public function getBookingStatusTypes()
    {
        return collect(collect(Booking::BOOKING_STATUS_TYPES)->map(function ($value, $key) {
            return [
                'id' => $key,
                'text' => $value
            ];
        })
        )->values();
    }

    public function getPaymentStatusTypes()
    {
        return collect(collect(Booking::PAYMENT_STATUS_TYPES)->map(function ($value, $key) {
            return [
                'id' => $key,
                'text' => $value
            ];
        })
        )->values();
    }

    public function userInfoUpdate($data)
    {
        $user = User::where('id', $data['id'])->first();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->save();
        return $user;
    }

    public function bookingInfoUpdate($data)
    {
        $booking = Booking::where('id', $data['id'])->first();
        $booking->way_type = $data['way_type'];
        $booking->pickup_date_time = $data['pickup_date_time'];
        $booking->return_date_time = $data['return_date_time'];
        $booking->total_distance = $data['total_distance'];
        $booking->car_type = $data['car_type'];
        $booking->price = round($data['price']);
        $booking->paid_amount = $data['paid_amount'];
        $booking->payment_status = $data['payment_status'];
        $booking->status = $data['status'];
        $booking->remarks = $data['remarks'] ?? null;
        $booking->followup_status =  $data['followup_status'] ?? null;
        $booking->driver_status =  $data['driver_status'] ?? null;
        $booking->save();
        return $booking;
    }

    public function driverUpdate($data)
    {
        $booking = Booking::where('id', $data['id'])->first();
        $booking->driver_id = $data['driver_id'] ?? null;
        $booking->driver_phone = $data['driver_phone'] ?? null;
        $booking->driver_name = $data['driver_name'] ?? null;
        $booking->driver_email = $data['driver_email'] ?? null;
        $booking->car_reg_no = $data['car_reg_no'] ?? null;
        $booking->save();
        return $booking;
    }

    public function locationUpdate($data)
    {
        $location = BookingLocation::where('id', $data['id'])->first();
        $location->address = $data['address'];
        $location->lat = $data['lat'];
        $location->lng = $data['lng'];
        $location->save();
        return $location;
    }

    public function tripStatistics(Request $data)
    {
        $totalCost = $grossMargin = 0;
        $bookingData = $this->bookingQuery($data)->get();
        $confirmedBooking = collect($bookingData)->where('status', 'confirmed')->count();
        $completed = collect($bookingData)->where('status', 'completed')->count();
        $canceled = collect($bookingData)->where('status', 'canceled')->count();
        $requested = collect($bookingData)->count();
        $totalRevenue = collect($bookingData)
            ->where('status', '!=', 'pending')
            ->where('status', '!=', 'canceled')
            ->sum('price');
        return [
            'requested' => number_format($requested),
            'confirmedBooking' => number_format($confirmedBooking),
            'completed' => number_format($completed),
            'canceled' => number_format($canceled),
            'totalRevenue' => number_format($totalRevenue),
            'totalCost' => number_format($totalCost),
            'grossMargin' => number_format($grossMargin)
        ];
    }

    public function isBookingUpdatePossible($data): bool
    {
        $booking = $this->getBookingById($data['booking_id']);
        if ($booking['status'] == 'driver_assigned' || $booking['driver_phone'] || $booking[''] || $booking['status'] == 'completed') {
            return false;
        }
        $requestedBy = request()->user()->user_code;
        $bookingUser = $booking['user']['user_code'];
        if ($requestedBy != $bookingUser) {
            return false;
        }
        if ($data['payment_status']) {
            if ($data['payment_status'] != 'unpaid') {
                return false;
            }
            if ($booking['paid_amount'] > 0){
                return  false;
            }
        }
        return true;
    }

    public function customerBookingUpdate($data)
    {
        $booking = $this->getBookingById($data['booking_id']);
        if ($data['pickup_date_time']) {
            $booking->pickup_date_time = $data['pickup_date_time'];
        }
        if ($data['return_date_time']) {
            $booking->return_date_time = $data['return_date_time'];
        }
        if ($data['payment_status']) {
            if ($booking->payment_status == $this->model()::PAYMENT_UNVERIFIED) {
                $this->sendPayLetterMail($booking);
            }
            $booking->payment_status = $data['payment_status'];
        }
        $booking->save();

        return $booking;
    }

    public function refundRequest($data): bool
    {
        $booking = $this->getBookingById($data['booking_id']);
        $completedPayments = collect($booking['payments'])
            ->where('status', 'Complete');
        $totalAmountNeedsToBeRefunded = collect($completedPayments)
            ->sum('amount');
        if (
            $totalAmountNeedsToBeRefunded > 0 &&
            $booking->payment_status != Booking::REFUND_REQUESTED &&
            $booking->payment_status != Booking::REFUNDED
        ) {
            $mailSettings = MailSettings::query()
                ->where('mail_type', 'refund_mail_accounts')
                ->first();
            $trxIds = collect($completedPayments)->pluck('transaction_id');
            $details['booking_details'] = $booking;
            $details['refund_amount'] = $totalAmountNeedsToBeRefunded;
            $details['trx_ids'] = $trxIds;
            Mail::to($mailSettings['to'])->cc($mailSettings['cc'])->send(new RefundRequestMail($details));
            $booking->payment_status = Booking::REFUND_REQUESTED;
            $booking->save();
            Mail::to($details['booking_details']['user']['email'])->send(new RefundRequestMailToCustomer($details));
            return true;
        } else {
            return false;
        }

    }

    public function getCustomerTripDetails($id)
    {

        $user = $this->userUpdate();

        return $this->booking
            ->query()
            ->with(['user', 'bookingLocation', 'selectedCarType', 'payments'])
            ->where('id', $id)
            ->where('user_id', $user['id'])
            ->first();
    }

    public function getCustomerLatestTrips($data)
    {

        try {
            $this->userUpdate();
        } catch (\Throwable $exception) {
            return array(false, false);
        }

        $ongoingTrip = $this->booking
            ->query()
            ->with(['user', 'bookingLocation', 'selectedCarType', 'payments'])
            ->whereHas('user', function ($q) {
                $q->where('user_code', request()->user()->user_code);
            })
            ->orderBy('pickup_date_time', 'ASC')
            ->where('status', $this->booking::ONGOING)
            ->get();

        $upcomingTrips = $this->booking
            ->query()
            ->with([
                'user',
                'bookingLocation',
                'selectedCarType',
                'payments' => function ($query) {
                    $query->where('status', 'Complete');
                }
            ])
            ->whereHas('user', function ($q) {
                $q->where('user_code', request()->user()->user_code);
            })
//            ->where('pickup_date_time', '>', Carbon::now())
            ->orderBy('pickup_date_time', 'ASC')
            ->where('status', $this->booking::CONFIRMED)
            ->get();

        return array($ongoingTrip, $upcomingTrips);
    }
}
