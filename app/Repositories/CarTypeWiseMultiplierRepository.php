<?php

namespace App\Repositories;

use App\Models\CarTypeWiseMultiplier;
use App\Models\FareSettings;
use Repository\Baserepository\BaseRepository;

class CarTypeWiseMultiplierRepository extends BaseRepository
{

    private CarTypeWiseMultiplier $carTypeWiseMultiplier;

    public function __construct(CarTypeWiseMultiplier $carTypeWiseMultiplier)
    {
        $this->carTypeWiseMultiplier = $carTypeWiseMultiplier;
    }

    function model(): CarTypeWiseMultiplier
    {
        return $this->carTypeWiseMultiplier;
    }

    function isCarTypeWiseMultiplierCreateUpdatePossible($data): bool
    {
        $query = $this->carTypeWiseMultiplier->query()
            ->where('car_type_wise_prices_id', $data['car_type_wise_prices_id'])
            ->where('fare_settings_id', $data['fare_settings_id']);

        if (isset($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }

        return !$query->exists();
    }


    public function deleteMultiplier($id){
       $data = $this->carTypeWiseMultiplier->findOrFail($id);
       if ($data){
           $data->delete();
           return true;
       }
       return false;
    }

}
