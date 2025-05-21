<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    public function handle($request, Closure $next)
    {
        // need to remove
        if (file_exists(storage_path() . "/installed")) {
            \App::setLocale(getActiveLanguage());
        }

        if (!file_exists(storage_path() . "/installed")) {
            return redirect()->route('LaravelUpdater::welcome');
        }

        $input = $request->all();
        $request->merge($input);

        return $next($request);
    }
}
