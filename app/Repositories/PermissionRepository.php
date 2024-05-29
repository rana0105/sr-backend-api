<?php

namespace Repository;

use App\Models\Permission;
use Repository\Baserepository\BaseRepository;

class PermissionRepository extends BaseRepository
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function model()
    {
        return $this->permission;
    }

    public function fetchAll($request, $perPage)
    {
        $searchkey = $request->searchkey;

        $query = $this->permission->newQuery();

        $query->orderBy('id', 'desc');

        if ($request->has('searchkey') && $request->searchkey && $request->searchkey != 'null') {
            $query->where('name', 'LIKE', '%' . $searchkey . '%');
        }

        if (!is_null($perPage))
            return $query->paginate($perPage);
        return $query->get();
    }
}
