<?php

namespace Workdo\Webhook\Listeners;

use App\Events\CreateTicket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Webhook\Entities\SendWebhook;

class CreateTicketLis
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

    public function handle(CreateTicket $event)
    {
        if (moduleIsActive('Webhook')) {
            $ticket = $event->ticket;
            $action = 'New Ticket';
            $module = 'general';

            SendWebhook::SendWebhookCall($module, $ticket, $action);
        }
    }
}
