<?php

namespace App\Repositories;
use App\Models\Campaign;
use App\Models\CampaignLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IpRequest;
use Repository\Baserepository\BaseRepository;

class CampaignRepository extends BaseRepository
{
    private Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function model(): Campaign
    {
       return $this->campaign;
    }

    public function getCampaigns($data){
        $limit = $data->get('limit', 50);
        return $this->campaign::query()
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    public function campaignLogStore($data){
        $campaign = $this->campaign::query()
            ->where('campaign_code', $data['campaign_code'])
            ->first();

        if($campaign){
            $campaignLog = new CampaignLog();
            $campaignLog->campaign_id = $campaign->id;
            $campaignLog->ip_address  = IpRequest::ip() ?? null;
            $campaignLog->device_info = IpRequest::userAgent() ?? null;
            $campaignLog->user_code = $data['user_code'] ?? null;
            $campaignLog->phone = $data['phone'] ?? null;
            $campaignLog->save();
            return $campaignLog;
        }
        return false;
    }

    public function campaignLogUpdate($data){
        $campaignLogId = base64_decode($data['campaign_log_id']);
        $campaignLog = CampaignLog::query()
            ->where('id', $campaignLogId)
            ->first();
        if ($campaignLog){
            $campaignLog->phone = request()->user()->mobile;
            $campaignLog->user_code = request()->user()->user_code;
            $campaignLog->save();
            return $campaignLog;
        }
        return false;
    }

    public function getCampaignLogs(Request $data){
        $limit = $data->get('limit', 50);
        $campaignCode = $data->get('campaign_code', null);
        return CampaignLog::query()
            ->with(['campaign'])
            ->orderBy('id', 'desc')
            ->when($data->get('campaign_id'), function ($query) use ($data){
                $query->where('campaign_id', $data->get('campaign_id'));
            })
            ->when($campaignCode, function ($query) use ($campaignCode){
                $query->whereHas('campaign', function ($q) use ($campaignCode) {
                    $q->where('campaign_code', 'like', '%' . $campaignCode . '%');
                });            })
            ->paginate($limit);
    }

    public function campaignCreate($data){
        $campaign = new Campaign();
        $campaign->campaign_name = $data['campaign_name'];
        $campaign->campaign_code = $data['campaign_code'];
        $campaign->created_by   = request()->user()->user_code;
        $campaign->save();
        return $campaign;
    }

    public function campaignUpdate($data){
        $campaign = Campaign::query()
            ->where('id', $data['id'])
            ->first();
        $campaign->campaign_name = $data['campaign_name'];
        $campaign->updated_by   = request()->user()->user_code;
        $campaign->save();
        return $campaign;
    }

}
