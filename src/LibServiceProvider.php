<?php

namespace Hanoivip\Activity;

use Illuminate\Support\ServiceProvider;
use Hanoivip\Activity\Services\ActivityDataService;

class LibServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/hanoivip'),
        ]);
        
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
    
    public function register()
    {
        if (config('activity.cfg') == 'array')
            $this->app->bind('IActivityDataService', ActivityDataService::class);
        //if (config('activity.cfg') == 'database')
        //    $this->app->bind('IActivityDataService', ActivityDataService::class);
    }
}
