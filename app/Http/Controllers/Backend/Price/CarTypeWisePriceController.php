<?php

namespace App\Http\Controllers\Backend\Price;

use App\Http\Controllers\Controller;
use App\Models\CarTypeWisePrice;
use App\Services\Price\CarTypeWisePriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarTypeWisePriceController extends Controller
{
    private CarTypeWisePriceService $carTypeWisePriceService;

    public function __construct(CarTypeWisePriceService $carTypeWisePriceService){
        $this->carTypeWisePriceService = $carTypeWisePriceService;
    }
    public function index(): JsonResponse
    {
        $data = $this->carTypeWisePriceService->getAll();
        return response()->json(['status' => 200, "data" => $data]);
    }

    public function find(Request $request): JsonResponse
    {
        $carTypePriceInfo = $request->validate([
            'id' => 'required|exists:App\Models\CarTypeWisePrice,id',
        ]);
        $data = $this->carTypeWisePriceService->findById($carTypePriceInfo['id']);
        return response()->json(['status' => 200, "data" => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'car_type_name'     => 'required',
            'sit_capacity'      => 'required',
            'minimum_fare'      => 'required',
            'per_km_fare'       => 'required',
            'waiting_charge'    => 'required',
            'night_stay_charge' => 'required',
            'image'             => 'required|image'
        ]);
        $carTypeWisePrice = $this->carTypeWisePriceService->create($data);
        if ($carTypeWisePrice){
            return response()->json(['status' => 200, "data" => $carTypeWisePrice, "message" => 'Car type wise price added successfully']);
        }
        return response()->json(['status' => 500, "message" => 'Car type wise price add unsuccessful']);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id'                => 'required|exists:App\Models\CarTypeWisePrice,id',
            'car_type_name'     => 'required',
            'sit_capacity'      => 'required',
            'minimum_fare'      => 'required',
            'per_km_fare'       => 'required',
            'waiting_charge'    => 'required',
            'night_stay_charge' => 'required',
            'image'             => 'nullable|image'
        ]);
        $carTypeWisePrice = $this->carTypeWisePriceService->update($data);
        if ($carTypeWisePrice){
            return response()->json(['status' => 200, "data" => $carTypeWisePrice, "message" => 'Car type wise price updated successfully']);
        }
        return response()->json(['status' => 500, "message" => 'Car type wise price update unsuccessful']);
    }

    public function kmWisePrice(Request $request){
        $data = $this->validate($request,[
            'km' => 'required'
        ]);
        $prices = $this->carTypeWisePriceService->kmWisePrice($data);
        return response()->json(['status' => 200, "data" => $prices]);
    }

    public function tripPrices(Request $request){
        $validator = Validator::make($request->all(), [
            'pickupPlaceId'    => 'required',
            'dropoffPlaceId'   => 'required',
            'way_type'         => 'required',
            'pickup_date_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'return_date_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }
        list($prices, $distance) = $this->carTypeWisePriceService->tripPrices($validator->getData());
        return response()->json(['status_code' => 200, 'success' => true , 'distance' => $distance, "data" => $prices]);
    }
}
