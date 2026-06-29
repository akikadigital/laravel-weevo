<?php

use Akika\LaravelWeevo\Facades\Weevo as WeevoFacade;
use Akika\LaravelWeevo\Weevo;

it('resolves the weevo facade', function () {
    expect(WeevoFacade::getFacadeRoot())
        ->toBeInstanceOf(Weevo::class);
});

it('resolves the same singleton as the container', function () {
    expect(WeevoFacade::getFacadeRoot())
        ->toBe(app('weevo'));
});

it('has the correct facade accessor', function () {
    $reflection = new ReflectionClass(WeevoFacade::class);

    $method = $reflection->getMethod('getFacadeAccessor');
    $method->setAccessible(true);

    expect($method->invoke(null))
        ->toBe('weevo');
});
