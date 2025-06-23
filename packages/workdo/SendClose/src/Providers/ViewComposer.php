<?php

namespace Workdo\SendClose\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{

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

    public function provides()
    {
        return [];
    }
}
