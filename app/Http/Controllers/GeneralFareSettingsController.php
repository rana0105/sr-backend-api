<?php

namespace App\Http\Controllers;

use App\Services\Settings\GeneralFareSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralFareSettingsController extends Controller
{
    private GeneralFareSettingsService $generalFareSettingsService;

    public function __construct(GeneralFareSettingsService $generalFareSettingsService){
        $this->generalFareSettingsService = $generalFareSettingsService;
    }

    public function index(){
        $data = $this->generalFareSettingsService->getAll();
        return response()->json(['data' => $data, 'status_code' => 200 ]);
    }

    public function show($id){
        $data = $this->generalFareSettingsService->findById($id);
        return response()->json(['data' => $data, 'status_code' => 200 ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'round_trip_multiplier' => 'required',
            'profit_value' => 'required',
            'waiting_charge_upto' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }

        $data = $this->generalFareSettingsService->store($validator->getData());
        if ($data){
            return response()->json(['data' => $data, 'status_code' => 200 ]);
        }
        return response()->json(['data' => $data, 'status_code' => 400 ], 400);

    }

    public function update($id, Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'round_trip_multiplier' => 'required',
            'profit_value' => 'required',
            'waiting_charge_upto' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }

        $data = $this->generalFareSettingsService->update($id, $validator->getData());
        return response()->json(['data' => $data, 'status_code' => 200 ]);
    }
}
