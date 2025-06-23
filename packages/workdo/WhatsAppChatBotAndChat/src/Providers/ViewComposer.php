<?php

namespace Workdo\WhatsAppChatBotAndChat\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{

    public function register()
    {
        view()->composer(['home'], function ($view) {
            $settings = getCompanyAllSettings();
            if ((moduleIsActive('WhatsAppChatBotAndChat')) && (isset($settings['whatsapp_chatbot_access_token']) &&  isset($settings['whatsapp_chatbot_phone_number_id']) && isset($settings['whatsapp_chatbot_phone_number']) && isset($settings['whatsapp_chatbot_access_token']))) {
                $view->getFactory()->startPush('whatsappchatbot',view('whats-app-chat-bot-and-chat::company.whatsappchatbot.index',compact('settings')));
            }
        });
    }

    public function provides()
    {
        return [];
    }
}
