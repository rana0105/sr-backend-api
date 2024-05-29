<?php

namespace App\Http\Controllers\Backend\User;

use Service\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponseTrait;
use App\Http\Requests\Auth\UsersRequest;

class UserController extends Controller
{
    use JsonResponseTrait;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->userService->fetchAll($request, $request->perPage);
        if (!$users) {
            return $this->bad('Something Wrong to Get User List!');
        }
        return $this->json('Show User List', $users);
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
    public function store(UsersRequest $request)
    {
        try {
            $user = $this->userService->store($request);
            if (!$user) {
                return $this->bad('User Not Created!');
            }
            return $this->json('User Create Successfully!', $user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return $this->bad('User Not Found!');
        }
        return $this->json('User Info Data!', $user);
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
    public function update(UsersRequest $request, $id)
    {
        $user = $this->userService->update($id, $request);
        if (!$user) {
            return $this->bad('User Not Updated!');
        }
        return $this->json('User update Successfully!', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userService->delete($id);
        if (!$user) {
            return $this->bad('User Not Found!');
        }
        return $this->json('User Deleted!', $user);
    }

    public function permissionsByUserId($id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return $this->bad('User Not Found!');
        }
        $permissions = $this->userService->allPermissions($user);
        return $this->json('User Existing Permissions!', $permissions);
    }

    public function updatePermissionByUser(Request $request, $id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return $this->bad('User Not Found!');
        }
        $permissions = $this->userService->updateUserPermissions($user, $request);
        if (!$permissions) {
            return $this->bad('Permission Not Updated!');
        }
        return $this->json('Permission update Successfully!', $permissions);
    }

    public function userFromUserService(Request $request)
    {
        $token = $request->header('Authorization');
        $user = $this->userService->searchUserByMobile($request->mobile, $token);
        if (!$user) {
            return $this->bad('User Not Found!');
        }
        return $this->json('User exsist for this input phone number!', $user);
    }
}
