<?php

use Akika\LaravelWeevo\Support\WeevoClient;
use Akika\LaravelWeevo\Support\WeevoCredentials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'weevo.env' => 'sandbox',
        'weevo.url.sandbox' => 'https://sandbox.weevo.test',
        'weevo.debug' => false,
        'weevo.verify_ssl' => false,
        'weevo.timeout' => 30,
        'weevo.retry.times' => 0,
        'weevo.retry.sleep' => 0,
        'weevo.token_ttl' => 3300,
    ]);

    Cache::flush();
});

function weevoClient(): WeevoClient
{
    return new WeevoClient(
        new WeevoCredentials(
            username: 'test-user',
            apiKey: 'test-key',
            apiSecret: 'test-secret',
        )
    );
}

it('loads base url and credentials', function () {
    $client = weevoClient();

    expect($client->getUrl())->toBe('https://sandbox.weevo.test')
        ->and($client->getUsername())->toBe('test-user')
        ->and($client->getApiKey())->toBe('test-key')
        ->and($client->getApiSecret())->toBe('test-secret');
});

it('gets and caches access token', function () {
    Http::fake([
        'https://sandbox.weevo.test/get-token' => Http::response([
            'token' => 'cached-token',
        ], 200),
    ]);

    $client = weevoClient();

    expect($client->getAccessToken())->toBe('cached-token');
    expect($client->getAccessToken())->toBe('cached-token');

    Http::assertSentCount(1);
});

it('returns null when token request fails', function () {
    Http::fake([
        'https://sandbox.weevo.test/get-token' => Http::response([
            'message' => 'Invalid credentials',
        ], 401),
    ]);

    expect(weevoClient()->getAccessToken())->toBeNull();

    Http::assertSentCount(1);
});

it('uses bearer token when token exists', function () {
    Http::fake([
        'https://sandbox.weevo.test/deliveries/create' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $client = weevoClient()->setToken('manual-token');

    $response = $client->post('/deliveries/create', [
        'order_id' => 'ORD-001',
    ]);

    expect($response->successful())->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sandbox.weevo.test/deliveries/create'
            && $request->hasHeader('Authorization', 'Bearer manual-token')
            && $request['order_id'] === 'ORD-001';
    });
});

it('uses cached bearer token when no manual token is set', function () {
    Http::fake([
        'https://sandbox.weevo.test/get-token' => Http::response([
            'token' => 'auto-token',
        ], 200),

        'https://sandbox.weevo.test/deliveries/create' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $client = weevoClient();

    $response = $client->post('/deliveries/create', [
        'order_id' => 'ORD-001',
    ]);

    expect($response->successful())->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sandbox.weevo.test/deliveries/create'
            && $request->hasHeader('Authorization', 'Bearer auto-token');
    });

    Http::assertSentCount(2);
});

it('falls back to credential headers when token request fails', function () {
    Http::fake([
        'https://sandbox.weevo.test/get-token' => Http::response([
            'message' => 'Invalid credentials',
        ], 401),

        'https://sandbox.weevo.test/deliveries/create' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $response = weevoClient()->post('/deliveries/create', [
        'order_id' => 'ORD-001',
    ]);

    expect($response->successful())->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sandbox.weevo.test/deliveries/create'
            && $request->hasHeader('username', 'test-user')
            && $request->hasHeader('apikey', 'test-key')
            && $request->hasHeader('apisecret', 'test-secret');
    });
});

it('can make get request with query parameters', function () {
    Http::fake([
        'https://sandbox.weevo.test/deliveries/TRIP-001/show*' => Http::response([
            'trip_id' => 'TRIP-001',
        ], 200),
    ]);

    $client = weevoClient()->setToken('manual-token');

    $response = $client->get('/deliveries/TRIP-001/show', [
        'include' => 'items',
    ]);

    expect($response->json('trip_id'))->toBe('TRIP-001');

    Http::assertSent(function ($request) {
        return str_starts_with($request->url(), 'https://sandbox.weevo.test/deliveries/TRIP-001/show')
            && str_contains($request->url(), 'include=items')
            && $request->hasHeader('Authorization', 'Bearer manual-token');
    });
});
