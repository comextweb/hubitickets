<?php

namespace App\Listeners;

use App\Events\CompanySettingMenuEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompanySettingMenuListener
{

    public function __construct()
    {
        //
    }
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Brand Settings'),
            'name' => 'brand-settings',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'logo-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Email Settings'),
            'name' => 'email-settings',
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'email-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Email Notification Settings'),
            'name' => 'email-notification-settings',
            'order' => 205,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'email-notification-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Storage Settings'),
            'name' => 'storage-settings',
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'storage-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('ReCaptcha Settings'),
            'name' => 'recaptcha-settings',
            'order' => 400,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'recaptcha-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('SEO Settings'),
            'name' => 'seo-settings',
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'seo-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Cookie Settings'),
            'name' => 'cookie-settings',
            'order' => 600,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'cookie-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Chat Gpt Key Settings'),
            'name' => 'chatgpt-settings',
            'order' => 700,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'chatgpt-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Cache Settings'),
            'name' => 'cache-settings',
            'order' => 800,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'cache-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
        $menu->add([
            'title' => __('Pusher Settings'),
            'name' => 'pusher-settings',
            'order' => 900,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'pusher-settings',
            'module' => $module,
            'permission' => 'settings manage'
        ]);
    }
}
