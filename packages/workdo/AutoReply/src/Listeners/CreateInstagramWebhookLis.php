<?php

namespace Workdo\AutoReply\Listeners;

use App\Events\CreateTicket;
use App\Models\Conversion;
use App\Models\Ticket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateInstagramWebhookLis
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
        if (moduleIsActive('AutoReply') && moduleIsActive('InstagramChat')) {
            $ticket_id = $event->ticket;
            $ticket = Ticket::where('type', 'Instagram')->where('id', $ticket_id->id)->first();
            $admin_user = User::where('type', 'admin')->first();
            if (isset($ticket)) {
                $settings = getCompanyAllSettings();
                $existingAutoReply = Conversion::where('ticket_id', $ticket->id)
                    ->where('description', $settings['auto_reply_message'])
                    ->exists();

                // If auto-reply message is not already present, then create a new conversion
                if (!$existingAutoReply) {
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
    }
}
