<?php

namespace App\Listeners;

use App\Events\CompanySettingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\App;

class CompanySettingListener
{
    public function __construct()
    {
        //
    }

    public function handle(CompanySettingEvent $event): void
    {
        // $module = 'Base';
        // $methodName = 'getEmailSection';
        // $controllerClass = "App\\Http\\Controllers\\SettingsController";
        // if (class_exists($controllerClass)) {
        //     $controller = App::make($controllerClass);
        //     if(method_exists($controller,$methodName)){
        //         $html = $event->html;
        //         $settings = $html->getSettings();
        //         $output = $controller->{$methodName}($settings);
        //         $html->add([
        //            'html' => $output->toHtml(),
        //            'order' => 200,
        //            'module' => $module,
        //            'permission' => 'settings manage'
        //         ]);
        //     }
        // }
    }
}
