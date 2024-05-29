<?php

namespace App\Services\Booking;

use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class BookingService
{
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository){
        $this->bookingRepository = $bookingRepository;
    }

    public function getBookings(Request $data){
        return $this->bookingRepository->getBookings($data);
    }

    public function getBookingsForExcel(Request $data){
        return $this->bookingRepository->getBookingsForExcel($data);
    }

    public function bookingDataForMarketingTeam(Request $data){
        return $this->bookingRepository->bookingDataForMarketingTeam($data);
    }

    public function create($data){
        return $this->bookingRepository->createBookingRequest($data);
    }

    public function getBookingById($id){
        return $this->bookingRepository->getBookingById($id);
    }

    public function getBookingStatusTypes(){
        return $this->bookingRepository->getBookingStatusTypes();
    }

    public function getPaymentStatusTypes(){
        return $this->bookingRepository->getPaymentStatusTypes();
    }

    public function userInfoUpdate($data){
        return $this->bookingRepository->userInfoUpdate($data);
    }
    public function bookingInfoUpdate($data){
        return $this->bookingRepository->bookingInfoUpdate($data);
    }
    public function driverInfoUpdate($data){
        return $this->bookingRepository->driverUpdate($data);
    }
    public function tripStatistics($data){
        return $this->bookingRepository->tripStatistics($data);
    }
    public function locationInfoUpdate($locations){
        foreach ($locations as $data) {
            $this->bookingRepository->locationUpdate($data);
        }
    }
    public function customerTripHistory($data){
        return $this->bookingRepository->customerTripHistory($data);
    }
    public function customerTripReorder($data){
        return $this->bookingRepository->customerTripReorder($data);
    }

    public function bookingUpdatePossible($data){
        return $this->bookingRepository->isBookingUpdatePossible($data);
    }

    public function customerBookingUpdate($data){
        return $this->bookingRepository->customerBookingUpdate($data);
    }

    public function refundRequest($data){
        return $this->bookingRepository->refundRequest($data);
    }

    public function getCustomerTripDetails($id){
        return $this->bookingRepository->getCustomerTripDetails($id);
    }

    public function getCustomerLatestTrips($data){
        return $this->bookingRepository->getCustomerLatestTrips($data);
    }

    public function sendDriverMail($data){
        return $this->bookingRepository->sendDriverMail($data);
    }

}
