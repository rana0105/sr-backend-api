<?php

namespace Repository;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Repository\Baserepository\BaseRepository;

class UserRepository extends BaseRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function model()
    {
        return $this->user;
    }

    public function fetchAll($request, $perPage)
    {
        $searchkey = $request->searchkey;

        $type = $request->get('type');
        $query = $this->user->newQuery();

        $query->orderBy('id', 'desc');

        if ($request->has('searchkey') && $request->searchkey && $request->searchkey != 'null') {
            $query->where(function ($query) use ($searchkey) {
                $query->where('name', 'LIKE', '%' . $searchkey . '%')
                    ->orWhere('phone', 'LIKE', '%' . $searchkey . '%');
            });
        }
        if ($request->has('type') && $request->type && $request->type != 'null') {
            $query->where('role_id', $type);
        }

        if (!is_null($perPage))
            return $query->paginate($perPage);
        return $query->get();
    }

    public function userPermissions($userCode)
    {
        return $this->user->with('permissions')->where('user_code', $userCode)->first();
    }

    public function  getAdminInformation(){
        $userApiUrl =  env('USER_SERVICE_URL').'auth';
        return Http::withHeaders([
            'Authorization' => request()->header('authorization')
        ])->post($userApiUrl, [
            'strategy' => 'MobileAndPassword',
            'mobile' => '01684323538',
            'secret' => '1234shuttle5678',
            'system' => 'Cockpit',
            'role' => 'Internal User',
        ])->json('data');
    }

    public function updateProfileInRentalService($profileData){
        $user = User::query()
            ->where('phone', request()->user()->mobile)
            ->first();
//        dd($user['user_code']);
        if ($user){
            try {
                if (!$user['user_code']){
                    $user->user_code = request()->user()->user_code;
                }
                if ($profileData['name']){
                    $user->name = $profileData['name'];
                }
                if ($profileData['email']){
                    $user->email = $profileData ['email'];
                }
                $user->save();
                return true;
            }catch (\Exception $exception){
                return false;
            }
        }else{
            try {
                $role = Role::query()->where('name', 'customer')->first();
                User::create([
                    'name' => $profileData['name'],
                    'phone' => request()->user()->mobile,
                    'user_code' => request()->user()->user_code,
                    'email' => $profileData['email'],
                    'role_id' => $role['id'] ?? null
                ]);
                return true;
            }catch (\Exception $exception){
                return false;
            }
        }

    }
    public function getReferralCode(){
        return Http::withHeaders([
            'Authorization' => request()->header('Authorization')
        ])->get(env('USER_SERVICE_URL') . 'v1/users/get-referral-code', [
            'user_code' => request()->user()->user_code,
        ])->json('data');
    }

    public function getUserInfo($userInfo){
        return $this->model()::query()
            ->where('user_code',$userInfo['user_code'])
            ->where('phone', $userInfo['mobile'])
            ->where('email', $userInfo['email'])
            ->get();
    }

}
