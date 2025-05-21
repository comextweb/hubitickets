<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        // For Company Role
        $admin = User::where('type', 'admin')->first();
        if (empty($admin)) {
            $admin = new User();
            $admin->name = 'Admin';
            $admin->email  = 'admin@example.com';
            $admin->password = Hash::make(1234);
            $admin->avatar =  'uploads/users-avatar/avatar.png';
            $admin->parent = 0;
            $admin->type = 'admin';
            $admin->mobile_number = "+911023012546";
            $admin->is_enable_login  = 1;
            $admin->lang =  'en';
            $admin->created_by =  0;
            $admin->save();

            $role = Role::where('name', 'admin')->where('guard_name', 'web')->exists();
            if (!$role) {
                $adminRole = Role::create(
                    [
                        'name' => 'admin',
                        'created_by' => 0,
                    ]
                );
            }
            $adminRole = Role::where('name', 'admin')->first();
            $admin->addRole($adminRole);
        }
        $adminPermission = [
            // dashboard
            'dashboard manage',
            // users
            'user manage',
            'user create',
            'user show',
            'user edit',
            'user delete',
            'user profile manage',
            'user reset password',
            'user login manage',
            // userlog
            'userlog manage',
            'userlog show',
            'userlog delete',
            // language
            'language manage',
            'language create',
            'language change',
            'language enable/disable',
            'language delete',
            // tickets
            'ticket manage',
            'ticket manage all',
            'ticket create',
            'ticket edit',
            'ticket show',
            'ticket delete',
            'ticket export',
            'ticket reply',
            'tiketnote store',
            // faq
            'faq manage',
            'faq create',
            'faq edit',
            'faq show',
            'faq delete',
            // knowledgebase
            'knowledgebase manage',
            'knowledgebase create',
            'knowledgebase edit',
            'knowledgebase show',
            'knowledgebase delete',
            // knowledgebase category
            'knowledgebase-category manage',
            'knowledgebase-category create',
            'knowledgebase-category show',
            'knowledgebase-category edit',
            'knowledgebase-category delete',
            // notification
            'notification-template manage',
            'notification-template view',
            'notification-template edit',
            // emailtemplate
            'email-template manage',
            'email-template edit',
            'email-template view',
            // ticket category
            'category manage',
            'category create',
            'category edit',
            'category delete',
            // ticket priority
            'priority manage',
            'priority create',
            'priority edit',
            'priority delete',
            //  roles permissions
            'role manage',
            'role create',
            'role edit',
            'role delete',
            // company setting
            'settings manage',
            // custom field
            'custom field manage',
            'custom field create',
            'custom field edit',
            'custom field delete',
        ];
        $adminRole = Role::where('name', 'admin')->first();
        if (!empty($adminRole)) {
            foreach ($adminPermission as $key => $value) {
                $permission = Permission::where('name', $value)->first();
                if (empty($permission)) {
                    $permission = Permission::create([
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'General',
                        'created_by' => $admin->id
                    ]);
                }
                if (!$adminRole->hasPermission($value)) {
                    $adminRole->givePermission($permission);
                }
            }
        }


        // For Agent Role
        $agent = Role::where('name', 'agent')->where('guard_name', 'web')->exists();
        if (!$agent) {
            $agentRole  = Role::create(
                [
                    'name' => 'agent',
                    'created_by' => $admin->id,
                ]
            );
        }
        $agentPermissions = [
            // dashboard
            'dashboard manage',
            // language
            'language manage',
            // tickets
            'ticket manage',
            'ticket show',
            'ticket edit',
            'ticket export',
            'ticket reply',
            'user profile manage',
        ];

        $agentRole = Role::Where('name', 'agent')->first();
        if (!empty($agentRole)) {
            foreach ($agentPermissions as $key => $value) {
                $permission = Permission::Where('name', $value)->first();
                if (empty($permission)) {
                    $permission = Permission::create([
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'General',
                        'created_by' => $admin->id
                    ]);
                }

                if (!$agentRole->hasPermission($value)) {
                    $agentRole->givePermission($permission);
                }
            }
        }


        $agent = User::where('type', 'agent')->first();

        try {
            $assigned_role = $agent->roles->first();
        } catch (\Exception $e) {
            $assigned_role = null;
        }
        if (!$assigned_role && !empty($agent)) {
            $agent->addRole($agentRole);
        }
    }
}
