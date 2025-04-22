<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleEnable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleName = null): Response
    {
        if (Auth::check()) {
            if ($moduleName != null) {
                $moduleName = explode('-', $moduleName);
                $status =  false;
                foreach ($moduleName as $module) {
                    $status = moduleIsActive($module);
                    if ($status == true) {
                        break;
                    }
                }
                if ($status == true) {
                    $activeModules = getActiveModules();
                    $activeModules = array_map('trim', $activeModules);
                    if (!empty(array_intersect($activeModules, $moduleName))) {
                        $response = $next($request);
                        return $response;
                    }
                }
                return redirect()->route('admin.dashboard')->with('error', 'Permission Denied.');
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Permission Denied.');
            }
        }
        $response = $next($request);
        return $response;
    }
}
