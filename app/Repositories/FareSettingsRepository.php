<?php

namespace App\Repositories;

use App\Models\FareSettings;
use Repository\Baserepository\BaseRepository;

class FareSettingsRepository extends BaseRepository
{

    private FareSettings $fareSettings;

    public function __construct(FareSettings $fareSettings)
    {
        $this->fareSettings = $fareSettings;
    }

    function model(): FareSettings
    {
        return $this->fareSettings;
    }

    function getFareSettings($data){
        $limit = $data->get('limit', 50);
        return $this->model()::query()
            ->with(['carTypeWiseMultiplier'])
            ->when($data['id'], function ($query) use ($data){
                $query->where('id',$data['id']);
            })
            ->when($data['from'], function ($query) use ($data){
                $query->where('from',$data['from']);
            })
            ->when($data['to'], function ($query) use ($data){
                $query->where('to',$data['to']);
            })
            ->paginate($limit);
    }

    function isCreateUpdatePossible($data){
        $query = $this->fareSettings->query()
            ->where('from', $data['from'])
            ->where('to', $data['to']);

        if (isset($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }

        return !$query->exists();
    }

}
