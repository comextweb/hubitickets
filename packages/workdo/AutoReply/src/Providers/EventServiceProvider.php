<?php

namespace Workdo\AutoReply\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\CreateTicket;
use Workdo\AutoReply\Listeners\CompanyMenuListener;
use Workdo\AutoReply\Listeners\CompanySettingListener;
use Workdo\AutoReply\Listeners\CompanySettingMenuListener;
use Workdo\AutoReply\Listeners\CreateInstagramWebhookLis;
use Workdo\AutoReply\Listeners\CreateTicketLis;

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
        CreateTicket::class => [
            CreateTicketLis::class,
            CreateInstagramWebhookLis::class,
        ]        
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
