<?php

namespace Workdo\AutoReply\Listeners;

use App\Events\CreateTicket;
use App\Models\Conversion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        if (moduleIsActive('AutoReply')) {
            $ticket = $event->ticket;
            $settings = getCompanyAllSettings();
            if (!empty($settings['is_enable_auto_reply']) && $settings['is_enable_auto_reply'] == 'on') {
                $conversion = new Conversion();
                $conversion->ticket_id = $ticket->id;
                $conversion->sender = defaultSenderId();
                $conversion->description = $settings['auto_reply_message'] ?? '';
                $conversion->save();
            }
        }
    }
}
