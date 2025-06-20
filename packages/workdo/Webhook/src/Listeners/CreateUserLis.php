<?php

namespace Workdo\Webhook\Listeners;

use App\Events\CreateUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Webhook\Entities\SendWebhook;

class CreateUserLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(CreateUser $event)
    {
        if(moduleIsActive('Webhook')){
            $user = $event->user;
            $action = 'New User';
            $module = 'general';

            SendWebhook::SendWebhookCall($module, $user, $action);
        }
    }
}
