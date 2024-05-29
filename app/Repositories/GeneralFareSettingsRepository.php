<?php

namespace App\Repositories;

use App\Models\FareSettings;
use App\Models\GeneralFareSettings;
use Repository\Baserepository\BaseRepository;

class GeneralFareSettingsRepository extends BaseRepository
{

    private GeneralFareSettings $generalFareSettings;

    public function __construct(GeneralFareSettings $generalFareSettings)
    {
        $this->generalFareSettings = $generalFareSettings;
    }

    function model(): GeneralFareSettings
    {
        return $this->generalFareSettings;
    }

}
