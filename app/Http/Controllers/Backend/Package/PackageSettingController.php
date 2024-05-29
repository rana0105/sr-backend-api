<?php

namespace App\Http\Controllers\Backend\Package;

use App\Http\Controllers\Controller;
use App\Services\Package\PackageSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PackageSettingController extends Controller
{
    private PackageSettingService $packageSettingService;

    public function __construct(PackageSettingService $packageSettingService){
        $this->packageSettingService = $packageSettingService;
    }

    public function index(){
        $packages = $this->packageSettingService->getPackages();
        return response()->json(['status' => 200, "data" => $packages]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'from_destination'    => 'required',
            'from_dest_place_id'  => 'required',
            'to_destination'      => 'required',
            'to_dest_place_id'    => 'required',
            'vehicle_type'        => 'required',
            'starting_price'      => 'required',
            'package_id'          => 'nullable|unique:package_settings',
            'status'              => [
                Rule::in(['Active', 'Inactive']),
            ],
            'trip_type'           => [
                Rule::in([1, 2]),
            ],
            'image'               => 'required|image'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406],406);
        }
        $data = $validator->getData();
        $packageSetting = $this->packageSettingService->create($data);
        if ($packageSetting){
            return response()->json(['status' => 200, "data" => $packageSetting, "message" => 'Package added successfully']);
        }
        return response()->json(['status' => 500, "message" => 'Package add unsuccessful']);
    }

    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'id'                  => 'required|exists:App\Models\PackageSetting,id',
            'from_destination'    => 'required',
            'from_dest_place_id'  => 'required',
            'to_destination'      => 'required',
            'to_dest_place_id'    => 'required',
            'vehicle_type'        => 'required',
            'starting_price'      => 'required',
            'package_id'          => 'nullable|unique:package_settings,package_id,'.$request['id'],
            'status'              => [
                Rule::in(['Active', 'Inactive']),
            ],
            'trip_type'           => [
                Rule::in([1, 2]),
            ],
            'image'               => 'nullable|image'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406],406);
        }
        $packageSetting = $this->packageSettingService->update($validator->getData());
        if ($packageSetting){
            return response()->json(['status' => 200, "data" => $packageSetting, "message" => 'Package updated successfully']);
        }
        return response()->json(['status' => 500, "message" => 'Package update unsuccessful']);
    }

    public function find($id)
    {
        $package = $this->packageSettingService->findById($id);
        if ($package){
            return response()->json(['status' => 200, "data" => $package]);
        }
        return response()->json(['status' => 404, "message" => 'Not Found']);

    }

}
