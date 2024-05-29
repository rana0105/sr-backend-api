<?php

namespace App\Http\Controllers;

use App\Services\GoogleApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Exception;

class GoogleApiController extends Controller
{
    private GoogleApiService $googleApiService;

    public function __construct(GoogleApiService $googleApiService){
        $this->googleApiService = $googleApiService;
    }
    public function placeDetails(Request $request): JsonResponse
    {
        try {
            $data = $this->googleApiService->placeDetails($request['location']);
            return response()->json(['status' => 200, 'place_details' => $data]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'message' => 'Please provide a valid location' ,
                'error' => $exception->getMessage() ]);

        }
    }

    public function queryAutoComplete(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'input' => 'Required',
        ]);
        try{
            $searchResults = $this->googleApiService->queryAutocomplete($data);
            return response()->json(['status' => 200, 'search_results' => $searchResults]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'error' => $exception->getMessage(),
                'message' => 'Please provide a valid input' ,
            ]);
        }
    }

    public function distance(Request $request){
        $data = $this->validate($request, [
            'addressOneLat' => 'Required',
            'addressOneLng' => 'Required',
            'addressTwoLat' => 'Required',
            'addressTwoLng' => 'Required'
        ]);
        try {
            $result = $this->googleApiService->getDistance($data);
            return response()->json(['status' => 200, 'distance' => $result]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => $exception->getCode(),
                'message' => 'Distance fetch not possible',
                'error'  => $exception->getMessage()
            ]);
        }

    }
}
