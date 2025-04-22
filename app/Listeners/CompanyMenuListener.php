<?php

namespace App\Listeners;

use App\Events\CompanyMenuEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CompanyMenuListener
{
    public function __construct()
    {
        //
    }

    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'dashboard',
            'parent' => null,
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.dashboard',
            'module' => $module,
            'permission' => 'dashboard manage'
        ]);
        $menu->add([
            'title' => __('User Management'),
            'icon' => 'users',
            'name' => 'user-management',
            'parent' => null,
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'title' => __('User'),
            'icon' => '',
            'name' => 'user',
            'parent' => 'user-management',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.users',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'title' => __('Roles'),
            'icon' => '',
            'name' => 'role',
            'parent' => 'user-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.roles',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        if (Auth::user()->hasRole('admin')) {
            $menu->add([
                'title' => __('Add-on Manager'),
                'icon' => 'layout-2',
                'name' => 'add-on-manager',
                'parent' => null,
                'order' => 300,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'admin.addon.list',
                'module' => $module,
                'permission' => ''
            ]);
        }
         $menu->add([
            'title' => __('Conversation'),
            'icon' => 'brand-hipchat',
            'name' => 'conversation',
            'parent' => null,
            'order' => 400,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.new.chat',
            'module' => $module,
            'permission' => 'ticket manage'
        ]);
        $menu->add([
            'title' => __('FAQ'),
            'icon' => 'question-mark',
            'name' => 'faq',
            'parent' => null,
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.faq',
            'module' => $module,
            'permission' => 'faq manage'
        ]);
        $menu->add([
            'title' => __('Knowledge Base'),
            'icon' => 'school',
            'name' => 'knowledge-base',
            'parent' => null,
            'order' => 1000,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.knowledge',
            'module' => $module,
            'permission' => 'knowledgebase manage'
        ]);
        $menu->add([
            'title' => __('Email Template'),
            'icon' => 'template',
            'name' => 'email-template',
            'parent' => null,
            'order' => 1100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'email_template.index',
            'module' => $module,
            'permission' => 'email-template manage'
        ]);
        $menu->add([
            'title' => __('Notification Template'),
            'icon' => 'notification',
            'name' => 'notification-template',
            'parent' => null,
            'order' => 1200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'notifications-templates.index',
            'module' => $module,
            'permission' => 'notification-template manage'
        ]);
        $menu->add([
            'title' => __('Custom Field'),
            'icon' => 'circle-plus',
            'name' => 'custom-field',
            'parent' => null,
            'order' => 1300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.custom-field.index',
            'module' => $module,
            'permission' => 'custom field manage'
        ]);
        $menu->add([
            'title' => __('Setup'),
            'icon' => 'affiliate',
            'name' => 'setup',
            'parent' => null,
            'order' => 1400,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.category',
            'module' => $module,
            'permission' => ['category manage', 'priority manage']
        ]);
        $menu->add([
            'title' => __('System Settings'),
            'icon' => 'settings',
            'name' => 'system-settings',
            'parent' => null,
            'order' => 1500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admin.settings.index',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        // $menu->add([
        //     'title' => __('System Settings'),
        //     'icon' => '',
        //     'name' => 'system-settings',
        //     'parent' => 'Settings',
        //     'order' => 5,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'admin.settings.index',
        //     'module' => $module,
        //     'permission' => 'settings manage'
        // ]);
    }
}
