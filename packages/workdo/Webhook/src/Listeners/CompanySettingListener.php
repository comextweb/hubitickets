<?php

namespace Workdo\Webhook\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        if (in_array('Webhook', $event->html->modules)) {
            $module = 'Webhook';
            $methodName = 'index';
            $controllerClass = "Workdo\\Webhook\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 3110,
                        'module' => $module,
                        'permission' => 'webhook manage'
                    ]);
                }
            }
        }
    }
}
