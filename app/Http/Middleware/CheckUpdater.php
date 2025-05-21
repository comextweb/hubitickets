<?php

namespace App\Http\Middleware;

use App\Facades\AddonFacade as AddOnFacade;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckUpdater
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    public function handle($request, Closure $next)
    {
        $updateEnabled = filter_var(config('installer.updaterEnabled'), FILTER_VALIDATE_BOOLEAN);

        switch ($updateEnabled) {
            case true:
                $canInstall = new \RachidLaasri\LaravelInstaller\Middleware\canInstall();

                if (! $canInstall->alreadyInstalled()) {
                    return redirect()->route('LaravelInstaller::welcome');
                }


                if ($this->alreadyUpdated() == false) {
                    abort(404);
                }
                break;

            case false:
            default:
                abort(404);
                break;
        }
        return $next($request);
    }

    // public function alreadyUpdated()
    // {
    //     $migrations = $this->getMigrations();
    //     $dbMigrations = $this->getExecutedMigrations();

    //     if (count($migrations) == count(value: $dbMigrations)) {
    //         return true;
    //     }

    //     return false;
    // }

    public function alreadyUpdated()
    {
        $alreadyRunnedMigrations = DB::table('migrations')->pluck('migration');
        $getAllModules = AddonFacade::allModules();
        $baseMigrationsFiles = collect(File::glob(database_path('migrations/*.php')))
            ->map(function ($path) {
                return File::name($path);
            });
        foreach ($getAllModules as $key => $module) {
            $directory = "packages/workdo/" . $module->name . "/src/Database/Migrations";

            $modulesMigrations = collect(File::glob("{$directory}/*.php"))->map(function ($path) {
                return File::name($path);
            });

            // Merge Modules Migrations files with basecode migrations files collection
            $baseMigrationsFiles = $baseMigrationsFiles->merge($modulesMigrations);
        }
        $pendingMigrations = $baseMigrationsFiles->diff($alreadyRunnedMigrations);

        if (count($pendingMigrations) > 0) {
            return true;
        }
        return false;
    }
}
