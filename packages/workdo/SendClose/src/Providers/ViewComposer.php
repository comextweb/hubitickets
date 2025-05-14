<?php

namespace Workdo\SendClose\Providers;

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
        view()->composer(['admin.chats.new-chat-messge'], function ($view) {
            if (moduleIsActive('SendClose')) {
                $view->with('isSendClose', true)->getFactory()->startPush('send-close', view('send-close::sendclose.send_close'));
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
