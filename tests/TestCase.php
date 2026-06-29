<?php

namespace Akika\LaravelWeevo\Tests;

use Akika\LaravelWeevo\WeevoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WeevoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('weevo.env', 'sandbox');
        $app['config']->set('weevo.username', 'test-user');
        $app['config']->set('weevo.api_key', 'test-key');
        $app['config']->set('weevo.api_secret', 'test-secret');
        $app['config']->set('weevo.url.sandbox', 'https://sandbox.weevo.test');
        $app['config']->set('weevo.debug', false);
        $app['config']->set('weevo.verify_ssl', false);
        $app['config']->set('weevo.timeout', 30);
        $app['config']->set('weevo.retry.times', 0);
        $app['config']->set('weevo.retry.sleep', 0);
    }
}
