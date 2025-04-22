<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('givepermission', function () {
    $files = [
        'storage/',
        'bootstrap/cache',
        'public',
        'packages/workdo/',
        'uploads/',
        'resources/lang/',
        '.env'
    ];
    foreach ($files as $file) {
        $output = [];
        $resultCode = 0;
        exec("sudo chmod -R 777 $file", $output, $resultCode);

        if ($resultCode !== 0) {
            $this->error("Failed to change permissions for $file. Output: " . implode("\n", $output));
        } else {
            $this->info("Permissions changed successfully for $file");
        }
    }

    // Clear various caches
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    $this->info('Permission Set Successfully & Cache Clear.');
})->purpose('Set The File Pemissions And Clear The Project Cache.');
