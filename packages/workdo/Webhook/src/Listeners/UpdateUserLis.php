<?php

namespace Workdo\Webhook\Listeners;

use App\Events\UpdateUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Webhook\Entities\SendWebhook;

class UpdateUserLis
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

    public function handle(UpdateUser $event)
    {
        if (moduleIsActive('Webhook')) {
            $user = $event->user;

            $web_array = [
                'Name' => $user->name,
                'Email' => $user->email,
                'Mobile Number' => $user->mobile_number,
                'Type' => $user->type,
            ];
            $action = 'Update User';
            $module = 'general';

            SendWebhook::SendWebhookCall($module, $web_array, $action);
        }
    }
}
