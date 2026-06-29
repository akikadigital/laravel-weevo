<?php

use Akika\LaravelWeevo\Weevo;
use Illuminate\Contracts\Foundation\Application;

it('registers the weevo singleton', function () {
    expect(app()->bound('weevo'))->toBeTrue();

    expect(app('weevo'))
        ->toBeInstanceOf(Weevo::class);
});

it('always resolves the same singleton instance', function () {
    $first = app('weevo');
    $second = app('weevo');

    expect($first)
        ->toBe($second);
});

it('merges the weevo configuration', function () {
    expect(config('weevo'))
        ->toBeArray();

    expect(config('weevo.env'))
        ->not->toBeNull();
});

it('can resolve through the application container', function () {
    $weevo = app(Application::class)->make('weevo');

    expect($weevo)
        ->toBeInstanceOf(Weevo::class);
});
