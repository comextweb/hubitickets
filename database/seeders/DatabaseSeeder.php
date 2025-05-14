<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utility;
use Illuminate\Http\Request;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PrioritySeeder::class);
        // Check if the incoming route is for updater then customfield seeder will not be run
        if (request()->route() && request()->route()->getName() != 'LaravelUpdater::database') {
            $this->call(CustomFieldSeeder::class);
        }
        $this->call(NotificationSeeder::class);
        $this->call(AiTemplateSeeder::class);
        $this->call(DefaultCompanySetting::class);
        $this->call(LanguageTableSeeder::class);
    }
}
