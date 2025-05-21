<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\AddOnDetails;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('AddOnDetails', function ($app) {
            return new AddOnDetails();
        });
        
    }


    public function boot()
    {
        
        //
        Schema::defaultStringLength(191);
    }
}
