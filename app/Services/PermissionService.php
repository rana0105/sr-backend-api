<?php

namespace Service;

use Repository\PermissionRepository;

class PermissionService
{
    private $permissionRepo;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepo = $permissionRepository;
    }

    public function fetchAll($request, $perPage)
    {
        return $this->permissionRepo->fetchAll($request, $perPage);
    }

    public function store($request)
    {
        return $this->permissionRepo->create($request->all());
    }

    public function getById($id)
    {
        return $this->permissionRepo->findByID($id);
    }

    public function update($id, $request)
    {
        return $this->permissionRepo->updateByID($id, $request->except('id'));
    }

    public function delete($id)
    {
        return $this->permissionRepo->deletedByID($id);
    }
}
