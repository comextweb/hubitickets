<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetCurrentCompany
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Si es dominio principal
        if (in_array($host,  config('tenancy.main_domains'))) {

            return response()->view('default_hubitickets');
        }
        
        // Extraer el subdominio (parte antes del primer punto)
        $subdomain = explode('.', $host)[0];
        

        // Buscar la empresa por subdominio
        $company = Company::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->firstOrFail();

        // Registrar la empresa actual
        app()->instance('currentCompany', $company);
        config(['app.tenant_home' => "/admin/dashboard"]);

        return $next($request);
    }

}