<?php

namespace Repository\Baserepository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{

    abstract function model();

    public function getAll(): Collection
    {
        return $this->model()::all();
    }

    public function getAllByPaginate(int $perPage = null)
    {
        return $this->model()::paginate($perPage);
    }

    public function findByID($id): Model | null
    {
        return $this->model()::find($id);
    }

    public function findOrFailByID($id): Model | null
    {
        return $this->model()::findOrFail($id);
    }

    public function create(array $modelData)
    {
        return $this->model()::create($modelData);
    }

    public function updateByID($id, array $modelData)
    {
        $model = $this->findOrFailByID($id);
        $model->update($modelData);
        return $model;
    }

    public function deletedByID($id)
    {
        $model = $this->findOrFailByID($id);
        $model->delete();
        return $model;
    }
}
