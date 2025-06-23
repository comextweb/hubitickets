<?php

namespace Workdo\Tags\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Tags';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Tags'),
            'icon' => 'ti ti-tag',
            'name' => 'tags',
            'parent' => null,
            'order' => 620,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'tags.index',
            'module' => $module,
            'permission' => 'tags manage'
        ]);
    }
}
