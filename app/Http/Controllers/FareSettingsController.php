<?php

namespace App\Http\Controllers;

use App\Services\Settings\FareSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FareSettingsController extends Controller
{
    private FareSettingsService $fareSettingsService;

    public function __construct(FareSettingsService $fareSettingsService){
        $this->fareSettingsService = $fareSettingsService;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $this->fareSettingsService->getAll($request);
        return response()->json(['data' => $data, 'status_code' => 200 ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }
        $settingsCreatePossible = $this->fareSettingsService->isCreateUpdatePossible($validator->getData());
        if ($settingsCreatePossible){
            $settingStore = $this->fareSettingsService->store($validator->getData());
            return response()->json(['data' => $settingStore, 'status_code' => 200, 'success' => true ]);
        }
        return response()->json(['status_code' => 400 , 'message' => 'From and to exists','success' => false]);
    }


    public function show($id): JsonResponse
    {
        $fareSettings = $this->fareSettingsService->findById($id);
        return response()->json(['data' => $fareSettings, 'status_code' => 200 ]);
    }


    public function update($id , Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406, 'success' => false], 406);
        }
        $settingsUpdatePossible = $this->fareSettingsService->isCreateUpdatePossible($validator->getData());

        if ($settingsUpdatePossible){
            $settingUpdate = $this->fareSettingsService->update($id, $validator->getData());
            return response()->json(['data' => $settingUpdate, 'status_code' => 200 , 'success' => true]);
        }

        return response()->json(['status_code' => 400 , 'message' => 'From and to exists','success' => false]);

    }

    public function multiplierValueCreate(Request $request){
        $validator = Validator::make($request->all(), [
            'car_type_wise_prices_id' => 'required|exists:App\Models\CarTypeWisePrice,id',
            'fare_settings_id' => 'required|exists:App\Models\FareSettings,id',
            'multiplier_value' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }
        $isCarTypeWiseMultiplierCreatePossible = $this->fareSettingsService->isMultiplierValueCreatePossible($validator->getData());

        if ($isCarTypeWiseMultiplierCreatePossible){
            $multiplierStore = $this->fareSettingsService->createMultiplier($validator->getData());
            return response()->json(['data' => $multiplierStore, 'status_code' => 200 , 'success' => true]);
        }
        return response()->json(['status_code' => 400 , 'message' => 'Similar data already exists','success' => false]);
    }

    public function multiplierValueUpdate($id, Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\CarTypeWiseMultiplier,id',
            'car_type_wise_prices_id' => 'required|exists:App\Models\CarTypeWisePrice,id',
            'fare_settings_id' => 'required|exists:App\Models\FareSettings,id',
            'multiplier_value' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }
        $isCarTypeWiseMultiplierCreatePossible = $this->fareSettingsService->isMultiplierValueCreatePossible($validator->getData());
        if ($isCarTypeWiseMultiplierCreatePossible){
            $multiplierUpdate = $this->fareSettingsService->updateMultiplier($id, $validator->getData());
            return response()->json(['data' => $multiplierUpdate, 'status_code' => 200 , 'success' => true]);
        }
        return response()->json(['status_code' => 400 , 'message' => 'Multiplier update not possible','success' => false]);
    }

    public function delete($id){
        if($this->fareSettingsService->deleteMultiplier($id)){
            return response()->json(['data' => 'Multiplier deleted', 'status_code' => 200 , 'success' => true]);
        }
        return response()->json(['status_code' => 400 , 'message' => 'Multiplier delete not possible', 'success' => false]);
    }

}
