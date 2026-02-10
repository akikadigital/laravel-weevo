<?php

namespace Akika\LaravelWeevo;

use Illuminate\Support\ServiceProvider;

class WeevoServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any application services.
        $this->app->bind('weevo', function () {
            return new Weevo(null, null, null);
        });
    }

    public function boot()
    {
        // Load package migrations
        if ($this->app->runningInConsole()) {

            // Publish the weevo config file
            $this->publishes([
                __DIR__ . '/../config/weevo.php' => config_path('weevo.php')
            ], 'config'); // Register InstallLaravelWeevoPackage command

            // Register InstallLaravelWeevoPackage command
            $this->commands([
                Commands\InstallLaravelWeevoPackage::class,
            ]);
        }
    }
}
