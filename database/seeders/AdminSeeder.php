<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::create(['name' => 'admin']);
        $admin = User::create([
            "user_code" => "SU-30490",
            "name" => "admin",
            "phone" => "01720292408",
            "email" => "admin@gmail.com",
            "status" => true,
            "role_id" => $role->id,
            "email_verified_at" => Carbon::now(),
            "password" => Hash::make(12345678)
        ]);
        $permissions = Permission::pluck('id', 'id')->all();
        $admin->permissions()->sync($permissions);
    }
}
