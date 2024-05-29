<?php

namespace Service;

use App\Helpers\HelperService;
use Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserService
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function fetchAll($request, $perPage)
    {
        return $this->userRepo->fetchAll($request, $perPage);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepo->create($request->except('password') + [
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return HelperService::responseException($e);
        }
    }

    public function getById($id)
    {
        return $this->userRepo->findByID($id);
    }

    public function update($id, $request)
    {
        return $this->userRepo->updateByID($id, $request->except('id'));
    }

    public function delete($id)
    {
        return $this->userRepo->deletedByID($id);
    }

    public function allPermissions($user)
    {
        $allPermissions = $user->permissions ? $user->permissions : [];
        $permissions = [];
        foreach ($allPermissions as $value) {
            array_push($permissions, $value->id);
        }
        return $permissions;
    }

    public function updateUserPermissions($user, $request)
    {
        // return $request->permissions;
        if ($request->has('permissions') && $request->permissions && $request->permissions != 'null') {
            return $user->permissions()->sync($request->permissions);
        }
        return DB::table('permission_user')->where('user_id', $user->id)->delete();
    }

    public function searchUserByMobile($mobile, $token)
    {
        $serviceUrl = env('USER_SERVICE_URL');
        $response = Http::withHeaders([
            'Authorization' => $token,
            'Accept' => 'Application/JSON'
        ])->get($serviceUrl . 'v1/users/search?', [
            'field' => 'mobile',
            'key' => $mobile
        ]);
        $data =  json_decode($response, true);
        return $data['data'][0];
    }
}
