<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\GoogleApiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleApiService
{
    private GoogleApiRepository $googleApiRepository;

    public function __construct(GoogleApiRepository $googleApiRepository){
        $this->googleApiRepository = $googleApiRepository;
    }
    public function placeDetails($location): array
    {
        return $this->googleApiRepository->placeDetails($location);
    }

    public function getDistance($data){
        return $this->googleApiRepository->getDistance($data);
    }

    public function queryAutocomplete($data){
        return $this->googleApiRepository->queryAutoComplete($data);
    }
}
