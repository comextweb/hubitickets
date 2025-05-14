<?php

namespace Workdo\Ratings\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Ratings';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Ratings'),
            'icon' => 'star',
            'name' => 'ratings',
            'parent' => null,
            'order' => 625,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'rating.index',
            'module' => $module,
            'permission' => 'ratings manage'
        ]);
    }
}
