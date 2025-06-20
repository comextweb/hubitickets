<?php

namespace Workdo\TicketNumber\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'TicketNumber';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Ticket Number Settings'),
            'name' => 'ticket-number-settings',
            'order' => 1130,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'ticket-number-sidenav',
            'module' => $module,
            'permission' => 'ticket-number manage'
        ]);
    }
}
