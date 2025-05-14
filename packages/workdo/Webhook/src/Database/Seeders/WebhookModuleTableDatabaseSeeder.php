<?php

namespace Workdo\Webhook\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\Webhook\Entities\WebhookModule;

class WebhookModuleTableDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $modules = [
            'general' => ['New User', 'New Ticket', 'Update User', 'Delete User', 'Delete Ticket']
        ];

        foreach ($modules as $module_name => $actions) {
            foreach ($actions as $action) {
                $ntfy = WebhookModule::where('submodule', $action)->where('module', $module_name)->count();
                if ($ntfy == 0) {
                    $new = new WebhookModule();
                    $new->module = $module_name;
                    $new->submodule = $action;
                    $new->save();
                }
            }
        }
    }
}
