<?php

namespace Repository;

use App\Models\Role;
use App\Models\User;
use Repository\Baserepository\BaseRepository;

class RoleRepository extends BaseRepository
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function model()
    {
        return $this->role;
    }

    public function fetchAll($request, $perPage)
    {
        $searchkey = $request->searchkey;

        $query = $this->role->newQuery();

        $query->orderBy('id', 'desc');

        if ($request->has('searchkey') && $request->searchkey && $request->searchkey != 'null') {
            $query->where('name', 'LIKE', '%' . $searchkey . '%');
        }

        if (!is_null($perPage))
            return $query->paginate($perPage);
        return $query->get();
    }

    public function customerRoleAssign(){
        $customerRoleID = Role::query()->where('name', 'customer')->first()['id'] ?? '';
        if ($customerRoleID){
          return User::query()
              ->whereNull('role_id')
              ->update([
                  'role_id' => $customerRoleID
              ]);
        }
        else{
            return false;
        }
    }
}
