<?php

namespace App\Http\Controllers\Backend\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingReorderRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class BookingController extends Controller
{
    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request): JsonResponse
    {
        $bookings = $this->bookingService->getBookings($request);
        return response()->json(['status' => 200, 'data' => $bookings]);
    }

    public function getBookingsForExcel(Request $request): JsonResponse
    {
        $bookings = $this->bookingService->getBookingsForExcel($request);
        return response()->json(['status' => 200, 'data' => $bookings, 'total' => count($bookings)]);
    }

    public function bookingDataForMarketingTeam(Request $request)
    {
        $data = $this->bookingService->bookingDataForMarketingTeam($request);
        return response()->json(['status' => 200, 'data' => $data]);
    }


    public function getBookingByID(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'id' => 'required|exists:App\Models\Booking,id'
        ]);
        $booking = $this->bookingService->getBookingById($data['id']);
        return response()->json(['status' => 200, 'data' => $booking]);
    }

    public function bookingStatusTypes(): JsonResponse
    {
        $bookingStatusTypes = $this->bookingService->getBookingStatusTypes();
        return response()->json(['status' => 200, 'data' => $bookingStatusTypes]);
    }

    public function paymentStatusTypes()
    {
        $paymentStatusTypes = $this->bookingService->getPaymentStatusTypes();
        return response()->json(['status' => 200, 'data' => $paymentStatusTypes]);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable',
            'phone' => 'nullable',
            'name' => 'nullable',
            'way_type' => 'required',
            'pickup_date_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'return_date_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
            'total_distance' => 'required',
            'package_id' => 'nullable',
            'car_type' => 'required|exists:App\Models\CarTypeWisePrice,id',
            'price' => 'required',
            'paid_amount' => 'nullable',
            'location_infos' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406],406);
        }
        $data = $validator->getData();
        $data['payment_status'] = Booking::PAYMENT_UNVERIFIED;
        $booked = $this->bookingService->create($data);
        if ($booked == Booking::USER_UPSERT_FAILED){
            return response()->json(['status_code' => 400, "message" => 'Invalid user information']);
        }
        if ($booked) {
            return response()->json(['status_code' => 200, "message" => 'Booking completed successfully', 'data' => $booked]);
        }
        return response()->json(['status_code' => 400, "message" => 'Booking unsuccessful']);
    }

    public function userInfoUpdate(Request $request): JsonResponse
    {
        $data = $this->bookingService->userInfoUpdate($request);
        return response()->json(['status' => 200, 'data' => $data, "message" => 'user info updated']);
    }

    public function bookingInfoUpdate(Request $request): JsonResponse
    {
        $data = $this->bookingService->bookingInfoUpdate($request);
        return response()->json(['status' => 200, 'data' => $data, "message" => 'booking updated']);
    }

    public function locationInfoUpdate(Request $request): JsonResponse
    {
        $this->bookingService->locationInfoUpdate($request->toArray());
        return response()->json(['status' => 200, "message" => 'Location updated']);
    }

    public function driverInfoUpdate(Request $request)
    {
        return $this->bookingService->driverInfoUpdate($request);
    }

    public function tripStatistics(Request $request): JsonResponse
    {
        $data = $this->bookingService->tripStatistics($request);
        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function customerTripHistory(Request $request)
    {
        $bookings = $this->bookingService->customerTripHistory($request);
        return response()->json(['status_code' => 200, 'data' => $bookings]);
    }

    public function tripReorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id'       => 'required|exists:App\Models\Booking,id',
            'pickup_date_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'return_date_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }

        $booked = $this->bookingService->customerTripReorder($request);
        if ($booked) {
            return response()->json(['message' => 'Booking done successfully', 'data' => $booked, 'status_code' => 200]);
        }
        return response()->json(['message' => 'Booking not done', 'status_code' => 403], 403);
    }

    public function customerBookingUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:App\Models\Booking,id',
            'pickup_date_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
            'return_date_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
            'payment_status'   => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406], 406);
        }

        $data = $validator->getData();

        $updatePossible = $this->bookingService->bookingUpdatePossible($data);

        if (!$updatePossible) {
            return response()->json(["message" => 'Booking update not possible', 'status_code' => 403], 403);
        }

        $updateBooking = $this->bookingService->customerBookingUpdate($data);

        if ($updateBooking) {
            return response()->json(['status_code' => 200, 'message' => 'Booking updated successfully', 'data' => $updateBooking]);
        }

        return response()->json(['status_code' => 400 , 'message' => 'Booking update unsuccessful']);
    }

    public function refundRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:App\Models\Booking,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $requested = $this->bookingService->refundRequest($validator->getData());
        if ($requested){
            return response()->json(['message' => 'Refund requested successfully', 'status'=> 'success']);
        }
        return response()->json(['message' => 'Refund request unsuccessful', 'status'=> 'error'], 400);
    }

    public function customerTripDetails($id){
        $tripData = $this->bookingService->getCustomerTripDetails($id);
        return response()->json(['status' => 200, 'data' => $tripData]);
    }

    public function customerLatestTrips(Request $request){
        list($ongoingTrips, $upcomingTrips) = $this->bookingService->getCustomerLatestTrips($request);
        if ($ongoingTrips || $upcomingTrips) {
            return response()->json(['status_code' => 200, 'ongoing_trips' => $ongoingTrips, 'upcoming_trips' => $upcomingTrips]);
        }
        return response()->json(['status_code' => 400, 'message' => 'No ongoing or upcoming trips']);
    }

    public function driverInfoMail($id){
       $booking =  $this->bookingService->getBookingById($id);
       if (!$booking['driver_phone'] || !$booking['driver_name']){
           return response()->json(['status_code' => 400, 'message' => 'Drivers name or phone is not available'], 400);
       }
       $this->bookingService->sendDriverMail($booking);
       return response()->json(['status_code' => 200, 'message' => 'Sent trip details to driver']);
    }

}
