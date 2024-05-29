<?php

namespace App\Services;

use App\Repositories\AirtableSyncRepository;

class AirtableSyncService
{
    private AirtableSyncRepository $airtableSyncRepository;

    public function __construct(AirtableSyncRepository $airtableSyncRepository){
        $this->airtableSyncRepository = $airtableSyncRepository;
    }

    public function sync(){
       return $this->airtableSyncRepository->sync();
    }

}
