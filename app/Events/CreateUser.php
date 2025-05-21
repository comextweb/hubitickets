<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $user;

    public function __construct($user,$request)
    {
        $this->request = $request;
        $this->user    = $user;
    }
}
