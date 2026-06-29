<?php

namespace Akika\LaravelWeevo;

use Illuminate\Support\ServiceProvider;

class WeevoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/weevo.php', 'weevo');

        $this->app->singleton('weevo', function () {
            return Weevo::default();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/weevo.php' => config_path('weevo.php'),
            ], 'weevo-config');

            $this->commands([
                Commands\InstallLaravelWeevoPackage::class,
            ]);
        }
    }
}
