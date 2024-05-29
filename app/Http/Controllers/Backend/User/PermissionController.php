<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Service\PermissionService;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponseTrait;

class PermissionController extends Controller
{
    use JsonResponseTrait;

    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = $this->permissionService->fetchAll($request, $request->perPage);
        if (!$permissions) {
            return $this->bad('Something Wrong to Get Permisson List!');
        }
        return $this->json('Show Permssion List', $permissions);
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
    public function store(Request $request)
    {
        $permmsion = $this->permissionService->store($request);
        if (!$permmsion) {
            return $this->bad('Permssion Not Created!');
        }
        return $this->json('Permssion Create Successfully!', $permmsion);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = $this->permissionService->getById($id);
        if (!$permission) {
            return $this->bad('Permisson Not Found!');
        }
        return $this->json('Permssion Info Data!', $permission);
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
    public function update(Request $request, $id)
    {
        $permission = $this->permissionService->update($id, $request);
        if (!$permission) {
            return $this->bad('Permisson Not Updated!');
        }
        return $this->json('Permssion update Successfully!', $permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = $this->permissionService->delete($id);
        if (!$permission) {
            return $this->bad('Permisson Not Found!');
        }
        return $this->json('Permssion Deleted!', $permission);
    }
}
