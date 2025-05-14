<?php

namespace Workdo\Tags\Providers;

use Illuminate\Support\ServiceProvider;
use Workdo\Tags\Entities\Tags;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['admin.chats.new-chat-messge','admin.chats.new-chat'], function ($view) {
            if (moduleIsActive('Tags')) {
                $view->with('isTags', true);
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
