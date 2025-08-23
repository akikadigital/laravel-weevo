<?php

namespace Akika\LaravelWeevo\Facades;

use Illuminate\Support\Facades\Facade;

class Weevo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-weevo';
    }
}
