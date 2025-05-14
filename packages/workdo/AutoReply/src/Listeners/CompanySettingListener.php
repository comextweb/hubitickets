<?php

namespace Workdo\AutoReply\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
       if(in_array('AutoReply',$event->html->modules))
       {
            $module = 'AutoReply';
            $methodName = 'index';
            $controllerClass = "Workdo\\AutoReply\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1120,
                        'module' => $module,
                        'permission' => 'autoreply manage'
                    ]);
                }
            }
       }
    }
}
