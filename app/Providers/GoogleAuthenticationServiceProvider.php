<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class GoogleAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            base_path('config/google2fa.php'), 'google2fa'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('routes/web.php'));
        $this->loadViewsFrom(base_path('resources/views'), 'google-authentication');
        $this->loadMigrationsFrom(base_path('database/migrations'));
        $this->autoPublishConfig();
        $this->registerMiddleware('2fa');
    }

    protected function autoPublishConfig()
    {
        $configPath = config_path('google2fa.php');
        $sourcePath = base_path('config/google2fa.php');
        if (!File::exists($configPath)) {
            File::copy($sourcePath, $configPath);
        }
    }

    protected function registerMiddleware($alias)
    {
        $router = $this->app['router'];
        $router->aliasMiddleware($alias, \App\Http\Middleware\Google2fa::class);
    }
}
