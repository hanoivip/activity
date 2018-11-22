<?php

namespace Hanoivip\Activity;

use Illuminate\Support\ServiceProvider;
use Hanoivip\Activity\Services\ArrayDataService;
use Hanoivip\Activity\Services\DbDataService;
use Hanoivip\Activity\Services\IActivityDataService;

class LibServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/activities.php' => config_path('activities.php'),
            __DIR__.'/../config/activitie.php' => config_path('activitie.php'),
            __DIR__.'/../views' => resource_path('views/vendor/hanoivip'),
            __DIR__.'/../lang' => resource_path('lang/vendor/hanoivip'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom( __DIR__.'/../lang', 'hanoivip');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
    
    public function register()
    {
        if (config('activity.cfg') == 'array')
        {
            $this->app->bind(IActivityDataService::class, ArrayDataService::class);
            $this->app->bind('IActivityDataService', ArrayDataService::class);
        }
        if (config('activity.cfg') == 'database')
        {
            $this->app->bind(IActivityDataService::class, DbDataService::class);
            $this->app->bind('IActivityDataService', DbDataService::class);
        }
    }
}
