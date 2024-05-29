<?php

namespace Service;

use Repository\RoleRepository;

class RoleService
{
    private $roleRepo;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepo = $roleRepository;
    }

    public function fetchAll($request, $perPage)
    {
        return $this->roleRepo->fetchAll($request, $perPage);
    }

    public function store($request)
    {
        return $this->roleRepo->create($request->all());
    }

    public function getById($id)
    {
        return $this->roleRepo->findByID($id);
    }

    public function update($id, $request)
    {
        return $this->roleRepo->updateByID($id, $request->except('id'));
    }

    public function delete($id)
    {
        return $this->roleRepo->deletedByID($id);
    }

    public function customerRoleAssign(){
        return $this->roleRepo->customerRoleAssign();
    }
}
