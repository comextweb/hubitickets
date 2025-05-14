<?php

namespace Workdo\Ratings\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\CreateTicket;
use App\Events\UpdateTicket;
use App\Events\UpdateTicketStatus;
use Workdo\Ratings\Listeners\CompanyMenuListener;
use Workdo\Ratings\Listeners\CompanySettingListener;
use Workdo\Ratings\Listeners\CompanySettingMenuListener;
use Workdo\Ratings\Listeners\UpdateTicketLis;
use Workdo\Ratings\Listeners\UpdateTicketStatusLis;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
        CompanyMenuEvent::class => [
            CompanyMenuListener::class,
        ],
        CompanySettingEvent::class => [
            CompanySettingListener::class,
        ],
        CompanySettingMenuEvent::class => [
            CompanySettingMenuListener::class,
        ],
        UpdateTicketStatus::class => [
            UpdateTicketStatusLis::class,
        ],
        CreateTicket::class => [
            UpdateTicketStatusLis::class,
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
