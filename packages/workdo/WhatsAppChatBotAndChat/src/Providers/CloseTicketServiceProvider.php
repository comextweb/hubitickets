<?php

namespace Workdo\WhatsAppChatBotAndChat\Providers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Workdo\WhatsAppChatBotAndChat\Entities\UserState;

class CloseTicketServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}

    public function boot()
    {
        if (moduleIsActive('WhatsAppChatBotAndChat')) {
            view()->composer(['admin.chats.new-chat-messge'], function ($view) {
                $currentUrl = url()->current();
                $ticketId = Request::segment(3);
                $ticket = Ticket::where('id', $ticketId)->first();
                if ($ticket) {
                    if ($ticket->type == 'Whatsapp') {
                        $checkCurrentExistingChat = UserState::where('ticket_id', $ticket->id)->where('state', 'existing_chat')->first();
                        if ($checkCurrentExistingChat) {
                            $view->getFactory()->startPush('whatsapp-close-ticket', view('whats-app-chat-bot-and-chat::close-button.close-button', compact('ticket', 'checkCurrentExistingChat')));
                        }
                    }
                }
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
