<?php

namespace Workdo\WhatsAppChatBotAndChat\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'WhatsAppChatBotAndChat';
        $menu = $event->menu;
        $menu->add([
            'title' => 'WhatsApp Chatbot & Chat Settings',
            'name' => 'whatsappchatbotandchat',
            'order' => 1100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'navigation' => 'whatsappchatbot-settings',
            'module' => $module,
            'permission' => 'WhatsAppChatBotAndChat manage'
        ]);
    }
}
