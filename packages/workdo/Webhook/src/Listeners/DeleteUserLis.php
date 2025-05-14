<?php

namespace Workdo\Webhook\Listeners;

use App\Events\DestroyUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Webhook\Entities\SendWebhook;

class DeleteUserLis
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

    public function handle(DestroyUser $event)
    {
        if (moduleIsActive('Webhook')) {
            $user = $event->user;
            $action = 'Delete User';
            $module = 'general';

            SendWebhook::SendWebhookCall($module, $user, $action);
        }
    }
}
