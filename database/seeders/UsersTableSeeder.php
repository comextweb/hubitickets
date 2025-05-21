<?php

namespace Database\Seeders;

use App\Models\Settings;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('type', 'admin')->first();
        $agent = User::where('type', 'agent')->first();
        if (empty($agent)) {
            $agent = new User();
            $agent->name = 'Agent';
            $agent->email  = 'agent@example.com';
            $agent->password = Hash::make(1234);
            $agent->avatar =  'uploads/users-avatar/avatar.png';
            $agent->parent = $admin->id;
            $agent->type = 'agent';
            $agent->is_enable_login  = 1;
            $agent->lang =  'en';
            $agent->created_by =  $admin->id;
            $agent->save();

            $agentRole = Role::where('name', 'agent')->first();
            if ($agentRole) {
                $agent->addRole($agentRole);
            }
        }
    }
}
