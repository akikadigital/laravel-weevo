<?php

namespace Akika\LaravelWeevo;

use Akika\LaravelWeevo\Support\WeevoClient;
use Akika\LaravelWeevo\Support\WeevoCredentials;

class Weevo
{
    public function __construct(
        protected WeevoClient $client
    ) {}

    public static function default(): self
    {
        return new self(
            new WeevoClient(
                new WeevoCredentials(
                    config('weevo.username'),
                    config('weevo.api_key'),
                    config('weevo.api_secret'),
                )
            )
        );
    }

    public static function using(
        WeevoCredentials|array $credentials
    ): self {

        if (is_array($credentials)) {
            $credentials = new WeevoCredentials(
                username: $credentials['username'],
                apiKey: $credentials['api_key'],
                apiSecret: $credentials['api_secret'],
            );
        }

        return new self(
            new WeevoClient($credentials)
        );
    }

    public function client(): WeevoClient
    {
        return $this->client;
    }

    public function authenticate(): ?array
    {
        $response = $this->client->post('/get-token');

        return $response->successful() ? $response->json() : null;
    }

    public function createDelivery(array $deliveryData): ?array
    {
        $response = $this->client->post('/deliveries/create', $deliveryData);

        return $response->successful() ? $response->json() : null;
    }

    public function getDelivery(string $tripId): ?array
    {
        $response = $this->client->post("/deliveries/{$tripId}/show");

        return $response->successful() ? $response->json() : null;
    }

    public function updatePaymentStatus(string $tripId, array $updateData): ?array
    {
        $response = $this->client->post("/deliveries/{$tripId}/update-payment-status", $updateData);

        return $response->successful() ? $response->json() : null;
    }
}
