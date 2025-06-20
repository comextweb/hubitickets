<?php

namespace Workdo\Webhook\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\CreateTicket;
use App\Events\CreateUser;
use App\Events\DestroyTicket;
use App\Events\DestroyUser;
use App\Events\UpdateUser;
use Workdo\Webhook\Listeners\CompanyMenuListener;
use Workdo\Webhook\Listeners\CompanySettingListener;
use Workdo\Webhook\Listeners\CompanySettingMenuListener;
use Workdo\Webhook\Listeners\CreateTicketLis;
use Workdo\Webhook\Listeners\CreateUserLis;
use Workdo\Webhook\Listeners\DeleteTicketLis;
use Workdo\Webhook\Listeners\DeleteUserLis;
use Workdo\Webhook\Listeners\UpdateUserLis;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
        CompanySettingEvent::class => [
            CompanySettingListener::class,
        ],
        CompanySettingMenuEvent::class => [
            CompanySettingMenuListener::class,
        ],
        CreateUser::class => [
            CreateUserLis::class,
        ],
        CreateTicket::class => [
            CreateTicketLis::class,
        ],
        UpdateUser::class => [
            UpdateUserLis::class,
        ],
        DestroyUser::class => [
            DeleteUserLis::class,
        ],
        DestroyTicket::class => [
            DeleteTicketLis::class,
        ],
    ];

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
