<?php

namespace App\Services\Settings;

use App\Repositories\CarTypeWiseMultiplierRepository;
use App\Repositories\FareSettingsRepository;
use App\Repositories\GeneralFareSettingsRepository;

class GeneralFareSettingsService
{
    private GeneralFareSettingsRepository $generalFareSettingsRepository;

    public function __construct(GeneralFareSettingsRepository $generalFareSettingsRepository)
    {
        $this->generalFareSettingsRepository = $generalFareSettingsRepository;
    }

    public function getAll(){
        return $this->generalFareSettingsRepository->getAll();
    }

    public function store($data){
        $data['created_by'] = request()->user()->user_code;
        if(count($this->getAll())<1){
            return $this->generalFareSettingsRepository->create($data);
        }
        return false;
    }

    public function findById($id){
        return $this->generalFareSettingsRepository->findByID($id);
    }

    public function update($id, $data){
        $data['updated_by'] = request()->user()->user_code;
        return $this->generalFareSettingsRepository->updateByID($id, $data);
    }
}
