<?php

namespace App\Http\Controllers\Backend\User;

use Service\RoleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponseTrait;
use App\Http\Requests\Auth\RoleRequest;

class RoleController extends Controller
{
    use JsonResponseTrait;

    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = $this->roleService->fetchAll($request, $request->perPage);
        if (!$roles) {
            return $this->bad('Something Wrong to Get Role List!');
        }
        return $this->json('Show Role List', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->store($request);
        if (!$role) {
            return $this->bad('Role Not Created!');
        }
        return $this->json('Role Create Successfully!', $role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->roleService->getById($id);
        if (!$role) {
            return $this->bad('Role Not Found!');
        }
        return $this->json('Role Info Data!', $role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleService->update($id, $request);
        if (!$role) {
            return $this->bad('Role Not Updated!');
        }
        return $this->json('Role update Successfully!', $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->roleService->delete($id);
        if (!$role) {
            return $this->bad('Role Not Found!');
        }
        return $this->json('Role Deleted!', $role);
    }

    public function customerRoleAssign(){
        $result = $this->roleService->customerRoleAssign();
        if (!$result) {
            return $this->bad('Role Not Updated! '.$result );
        }
        return $this->json('Role update Successfully! '. $result);
    }
}
