<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Models\MailSettings;
use App\Services\Settings\MailSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MailSettingsController extends Controller
{
    private MailSettingsService $mailSettingsService;

    public function __construct(MailSettingsService $mailSettingsService){
        $this->mailSettingsService = $mailSettingsService;
    }

    public function index(): JsonResponse
    {
        $data = $this->mailSettingsService->getAll();
        return response()->json(['data' => $data, 'status_code' => 200 ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mail_type' => 'required|unique:mail_settings',
            'to' => 'required',
            'cc' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'success' => false, 'status_code' => 406], 406);
        }
        $settingStore = $this->mailSettingsService->store($validator->getData());
        return response()->json(['data' => $settingStore, 'status_code' => 200, 'success' => true ]);
    }


    public function show($id): JsonResponse
    {
        $mailSettings = $this->mailSettingsService->findById($id);
        return response()->json(['data' => $mailSettings, 'status_code' => 200 ]);
    }


    public function update($id , Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mail_type' => 'required|unique:mail_settings,mail_type,' . $id,
            'to' => 'required',
            'cc' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status_code' => 406, 'success' => false], 406);
        }
        $settingUpdate = $this->mailSettingsService->update($id, $validator->getData());

        return response()->json(['data' => $settingUpdate, 'status_code' => 200 , 'success' => true]);
    }

    public function getMailTypes(){
        $mailTypes = $this->mailSettingsService->getMailTypes();
        return response()->json(['data' => $mailTypes, 'status_code' => 200 ]);
    }

}
