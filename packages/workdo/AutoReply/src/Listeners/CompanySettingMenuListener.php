<?php

namespace Workdo\AutoReply\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'AutoReply';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Auto Reply Settings'),
            'name' => 'autoreply',
            'order' => 1120,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'navigation' => 'autoreply-sidenav',
            'module' => $module,
            'permission' => 'autoreply manage'
        ]);
    }
}
