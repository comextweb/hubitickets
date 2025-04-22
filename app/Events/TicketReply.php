<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketReply
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $conversion;

    public function __construct($conversion,$request)
    {
        $this->request    = $request;
        $this->conversion = $conversion;
    }
}
