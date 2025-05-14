<?php

namespace Workdo\Webhook\Listeners;

use App\Events\DestroyTicket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Webhook\Entities\SendWebhook;

class DeleteTicketLis
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

    public function handle(DestroyTicket $event)
    {
        if (moduleIsActive('Webhook')) {
            $ticket = $event->ticket;
            $action = 'Delete Ticket';
            $module = 'general';

            SendWebhook::SendWebhookCall($module, $ticket, $action);
        }
    }
}
