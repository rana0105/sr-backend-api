<?php

namespace App\Services;

use App\Repositories\CampaignRepository;
use Illuminate\Http\Request;

class CampaignService
{
    private CampaignRepository $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository){
        $this->campaignRepository = $campaignRepository;
    }

    public function getCampaigns($data){
        return $this->campaignRepository->getCampaigns($data);
    }

    public function campaignLogStore($data){
        return $this->campaignRepository->campaignLogStore($data);
    }

    public function campaignLogUpdate($data){
        return $this->campaignRepository->campaignLogUpdate($data);
    }

    public function getCampaignLogs(Request $data){
        return $this->campaignRepository->getCampaignLogs($data);
    }

    public function campaignCreate($data){
        return $this->campaignRepository->campaignCreate($data);
    }

    public function campaignUpdate($data){
        return $this->campaignRepository->campaignUpdate($data);
    }
}
