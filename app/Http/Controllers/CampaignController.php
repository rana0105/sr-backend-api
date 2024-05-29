<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Services\AirtableSyncService;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Request as IpRequest;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    private CampaignService $campaignService;

    public function __construct(CampaignService $campaignService){
        $this->campaignService = $campaignService;
    }
    public function index(Request $request){

        $campaigns = $this->campaignService->getCampaigns($request);

        return response()->json([ 'message' => 'Campaigns',
            'success' => true ,
            'total' => count($campaigns),
            'data' => $campaigns]
        );
    }
    public function campaignLogStore(Request $request){

        $validator = Validator::make($request->all(), [
            'campaign_code' => 'required',
            'user_code'  => 'nullable',
            'phone'  => 'nullable'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 400);
        }

        $data = $this->campaignService->campaignLogStore($validator->getData());
        if($data){
            return response()->json(['campaign_log_id' => base64_encode($data['id']), 'message' => 'Campaign log stored successfully', 'success' => true ] , 200);
        }
        return response()->json(['message' => 'Campaign log create failed', 'success' => false ] , 400);

    }
    public function campaignLogUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'campaign_log_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 400);
        }

        $data = $this->campaignService->campaignLogUpdate($validator->getData());
        if($data){
            return response()->json(['campaign_log_id' => base64_encode($data['id']), 'message' => 'Campaign log updated successfully', 'success' => true ] , 200);
        }
        return response()->json(['message' => 'Campaign log update failed', 'success' => false ] , 400);

    }

    public function getCampaignLogs(Request $request){
        $data = $this->campaignService->getCampaignLogs($request);
        return response()->json([
            'message' => 'Campaign logs',
            'status' => true,
            'total' => count($data),
            'data' => $data
        ]);
    }

    public function campaignCreate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'campaign_name' => 'required',
            'campaign_code' => 'required|unique:campaigns|max:255'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 400);
        }

        $data = $this->campaignService->campaignCreate($validator->getData());

        return  response()->json([
            'message' => 'Campaign created',
            'status' => true,
            'data' => $data
        ]);
    }

    public function campaignUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\Campaign,id',
            'campaign_name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 400);
        }
        $data = $this->campaignService->campaignUpdate($validator->getData());
        return  response()->json([
            'message' => 'Campaign updated',
            'status' => true,
            'campaignLogId' => $data
        ]);
    }
}
