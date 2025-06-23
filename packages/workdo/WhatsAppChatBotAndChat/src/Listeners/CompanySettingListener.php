<?php

namespace Workdo\WhatsAppChatBotAndChat\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        if (in_array('WhatsAppChatBotAndChat', $event->html->modules)) {
            $module = 'WhatsAppChatBotAndChat';
            $methodName = 'index';
            $controllerClass = "Workdo\\WhatsAppChatBotAndChat\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1100,
                        'module' => $module,
                        'permission' => 'WhatsAppChatBotAndChat manage'
                    ]);
                }
            }
        }
    }
}
