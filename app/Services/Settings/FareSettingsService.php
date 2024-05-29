<?php

namespace App\Services\Settings;

use App\Repositories\CarTypeWiseMultiplierRepository;
use App\Repositories\FareSettingsRepository;

class FareSettingsService
{
    private FareSettingsRepository $fareSettingsRepository;
    private CarTypeWiseMultiplierRepository $carTypeWiseMultiplierRepository;

    public function __construct(
        FareSettingsRepository $fareSettingsRepository,
        CarTypeWiseMultiplierRepository $carTypeWiseMultiplierRepository)
    {
        $this->fareSettingsRepository = $fareSettingsRepository;
        $this->carTypeWiseMultiplierRepository = $carTypeWiseMultiplierRepository;
    }

    public function getAll($data){
        return $this->fareSettingsRepository->getFareSettings($data);
    }

    public function store($data){
        return $this->fareSettingsRepository->create($data);
    }

    public function findById($id){
        return $this->fareSettingsRepository->findByID($id);
    }

    public function update($id, $data){
        return $this->fareSettingsRepository->updateByID($id, $data);
    }

    public function isCreateUpdatePossible($data){
        return $this->fareSettingsRepository->isCreateUpdatePossible($data);
    }

    public function isMultiplierValueCreatePossible($data){
        return $this->carTypeWiseMultiplierRepository->isCarTypeWiseMultiplierCreateUpdatePossible($data);
    }

    public function createMultiplier($data){
        return $this->carTypeWiseMultiplierRepository->create($data);
    }

    public function updateMultiplier($id, $data){
        return $this->carTypeWiseMultiplierRepository->updateByID($id, $data);
    }

    public function deleteMultiplier($id){
        return $this->carTypeWiseMultiplierRepository->deleteMultiplier($id);
    }
}
