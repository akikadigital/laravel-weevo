<?php

use Akika\LaravelWeevo\Support\WeevoCredentials;

it('stores credentials correctly', function () {
    $credentials = new WeevoCredentials(
        username: 'merchant-user',
        apiKey: 'api-key',
        apiSecret: 'api-secret',
    );

    expect($credentials->username)->toBe('merchant-user')
        ->and($credentials->apiKey)->toBe('api-key')
        ->and($credentials->apiSecret)->toBe('api-secret');
});

it('generates the expected cache key', function () {
    $credentials = new WeevoCredentials(
        username: 'merchant-user',
        apiKey: 'api-key',
        apiSecret: 'api-secret',
    );

    expect($credentials->cacheKey())
        ->toBe(sprintf(
            '%s:%s',
            'merchant-user',
            md5('api-key')
        ));
});
