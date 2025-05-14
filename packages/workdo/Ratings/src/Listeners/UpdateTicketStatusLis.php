<?php

namespace Workdo\Ratings\Listeners;

use App\Events\UpdateTicketStatus;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\Crypt;

class UpdateTicketStatusLis
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

    public function handle($event)
    {
        $ticket = $event->ticket;
        $status = $event->ticket->status;
        $company_settings = getCompanyAllSettings();
        $user = User::find($ticket->is_assign);

        if(isset($company_settings['ticket_status']) && $company_settings['ticket_status'] == $status){
            if ((!empty($company_settings['Ticket Rating']) && $company_settings['Ticket Rating']  == true)) {
                $uArr = [
                    'customer_name' => $event->ticket->name,
                    'user_name'     => !empty($user) ? $user->name : '-',
                    'rating_url'    => '<a href="' . route('ticket.rating', ['ticket' => Crypt::encrypt($event->ticket->id)]) . '">Rating Link</a>',
                ];
                Utility::sendEmailTemplate('Ticket Rating', [$event->ticket->email], $uArr);
            }
        }
    }
}
