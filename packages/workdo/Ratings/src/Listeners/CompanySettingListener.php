<?php

namespace Workdo\Ratings\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        if (in_array('Ratings', $event->html->modules)) {
            $module = 'Ratings';
            $methodName = 'index';
            $controllerClass = "Workdo\\Ratings\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1140,
                        'module' => $module,
                        'permission' => 'ratings manage'
                    ]);
                }
            }
        }
    }
}
