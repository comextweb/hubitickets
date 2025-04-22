<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AddonFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AddOnDetails';
    }
}
