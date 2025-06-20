<?php

namespace Workdo\TicketNumber\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['admin.tickets.index', 'admin.tickets.edit', 'ticket-kanban::ticket-kanban.index', 'show', 'admin.chats.new-chat', 'admin.chats.new-chat-messge', 'admin', 'export-conversations::conversation.pdf'], function ($view) {
            if (moduleIsActive('TicketNumber')) {
                $view->with('isTicketNumberActive', true);
            }
        });
    }
    public function register()
    {
        //
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
