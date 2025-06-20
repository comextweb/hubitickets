<?php

namespace Workdo\TicketNumber\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Workdo\TicketNumber\Entities\TicketNumber;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'TicketNumber';

        $permissions  = [
            'ticket-number manage'
        ];

        TicketNumber::ticketNumberPrefix();

        $adminRole = Role::where('name','admin')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 1,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$adminRole->hasPermission($value))
                {
                    $adminRole->givePermission($permission);
                }
            }
        }
    }
}
