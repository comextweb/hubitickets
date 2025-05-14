<?php

namespace Workdo\Ratings\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Ratings';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Ratings'),
            'name' => 'ratings',
            'order' => 1140,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'ratings_sidenav',
            'module' => $module,
            'permission' => 'ratings manage'
        ]);
    }
}
