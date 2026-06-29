<?php

use Akika\LaravelWeevo\Weevo;
use Akika\LaravelWeevo\Support\WeevoClient;
use Akika\LaravelWeevo\Support\WeevoCredentials;
use Illuminate\Http\Client\Response;

afterEach(function () {
    \Mockery::close();
});

function fakeResponse(bool $successful, array $json = []): Response
{
    $response = \Mockery::mock(Response::class);

    $response->shouldReceive('successful')
        ->andReturn($successful);

    if ($successful) {
        $response->shouldReceive('json')
            ->andReturn($json);
    }

    return $response;
}

it('can create default weevo instance from config', function () {
    config([
        'weevo.username' => 'test-user',
        'weevo.api_key' => 'test-key',
        'weevo.api_secret' => 'test-secret',
    ]);

    $weevo = Weevo::default();

    expect($weevo)->toBeInstanceOf(Weevo::class)
        ->and($weevo->client())->toBeInstanceOf(WeevoClient::class);
});

it('can create weevo instance using credentials object', function () {
    $credentials = new WeevoCredentials(
        username: 'merchant-user',
        apiKey: 'merchant-key',
        apiSecret: 'merchant-secret',
    );

    $weevo = Weevo::using($credentials);

    expect($weevo)->toBeInstanceOf(Weevo::class)
        ->and($weevo->client())->toBeInstanceOf(WeevoClient::class);
});

it('can create weevo instance using credentials array', function () {
    $weevo = Weevo::using([
        'username' => 'merchant-user',
        'api_key' => 'merchant-key',
        'api_secret' => 'merchant-secret',
    ]);

    expect($weevo)->toBeInstanceOf(Weevo::class)
        ->and($weevo->client())->toBeInstanceOf(WeevoClient::class);
});

it('authenticates successfully', function () {
    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/get-token')
        ->andReturn(fakeResponse(true, [
            'token' => 'test-token',
        ]));

    $result = (new Weevo($client))->authenticate();

    expect($result)->toBe([
        'token' => 'test-token',
    ]);
});

it('returns null when authentication fails', function () {
    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/get-token')
        ->andReturn(fakeResponse(false));

    $result = (new Weevo($client))->authenticate();

    expect($result)->toBeNull();
});

it('creates delivery successfully', function () {
    $payload = [
        'external_id' => 'ORD-001',
        'customer_name' => 'John Doe',
    ];

    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/create', $payload)
        ->andReturn(fakeResponse(true, [
            'success' => true,
            'trip_id' => 'TRIP-001',
        ]));

    $result = (new Weevo($client))->createDelivery($payload);

    expect($result)->toBe([
        'success' => true,
        'trip_id' => 'TRIP-001',
    ]);
});

it('returns null when create delivery fails', function () {
    $payload = [
        'external_id' => 'ORD-001',
    ];

    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/create', $payload)
        ->andReturn(fakeResponse(false));

    $result = (new Weevo($client))->createDelivery($payload);

    expect($result)->toBeNull();
});

it('gets delivery successfully', function () {
    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/TRIP-001/show')
        ->andReturn(fakeResponse(true, [
            'trip_id' => 'TRIP-001',
            'status' => 'pending',
        ]));

    $result = (new Weevo($client))->getDelivery('TRIP-001');

    expect($result)->toBe([
        'trip_id' => 'TRIP-001',
        'status' => 'pending',
    ]);
});

it('returns null when get delivery fails', function () {
    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/TRIP-001/show')
        ->andReturn(fakeResponse(false));

    $result = (new Weevo($client))->getDelivery('TRIP-001');

    expect($result)->toBeNull();
});

it('updates payment status successfully', function () {
    $payload = [
        'payment_status' => 'paid',
        'transaction_code' => 'MPESA123',
    ];

    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/TRIP-001/update-payment-status', $payload)
        ->andReturn(fakeResponse(true, [
            'success' => true,
            'payment_status' => 'paid',
        ]));

    $result = (new Weevo($client))->updatePaymentStatus('TRIP-001', $payload);

    expect($result)->toBe([
        'success' => true,
        'payment_status' => 'paid',
    ]);
});

it('returns null when update payment status fails', function () {
    $payload = [
        'payment_status' => 'failed',
    ];

    $client = \Mockery::mock(WeevoClient::class);

    $client->shouldReceive('post')
        ->once()
        ->with('/deliveries/TRIP-001/update-payment-status', $payload)
        ->andReturn(fakeResponse(false));

    $result = (new Weevo($client))->updatePaymentStatus('TRIP-001', $payload);

    expect($result)->toBeNull();
});
