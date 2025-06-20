<?php

namespace Workdo\TicketNumber\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        if(in_array('TicketNumber',$event->html->modules))
        {
            $module = 'TicketNumber';
            $methodName = 'index';
            $controllerClass = "Workdo\\TicketNumber\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1130,
                        'module' => $module,
                        'permission' => 'ticket-number manage'
                    ]);
                }
            }
        }
    }
}
