<?php

namespace Akika\LaravelWeevo;

use Akika\LaravelWeevo\Traits\WeevoConnect;

class Weevo
{
    public string $environment;
    public bool $debugMode;
    public string $url;

    public string $username;
    public string $apiKey;
    public string $apiSecret;

    // Your code here
    use WeevoConnect;

    public function __construct(?string $username, ?string $apiKey, ?string $apiSecret)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->environment = config('weevo.env');
        $this->debugMode = config('weevo.debug');
        $this->url = config('weevo.url.' . $this->environment);
    }

    public function setCredentials(string $username, string $apiKey, string $apiSecret): void
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function createDelivery(array $deliveryData): ?array
    {
        $url = rtrim($this->url, '/') . '/deliveries/create';

        $response = $this->makeRequest($url, $deliveryData);

        if ($response->successful()) {
            return $response->json();
        } else {
            if ($this->debugMode) {
                info('------------------- Create Delivery Error -------------------');
                info('createDelivery response: ' . $response->body());
                info('------------------- End Create Delivery Error -------------------');
            }
            return null;
        }
    }

    public function getDelivery(string $tripId): ?array
    {
        $url = rtrim($this->url, '/') . '/deliveries/show/' . $tripId;

        $response = $this->makeRequest($url, []);

        if ($response->successful()) {
            return $response->json();
        } else {
            if ($this->debugMode) {
                info('------------------- Get Order Error -------------------');
                info('getOrder response: ' . $response->body());
                info('------------------- End Get Order Error -------------------');
            }
            return null;
        }
    }

    public function getDeliveryStatus(string $tripId): ?array
    {
        $url = rtrim($this->url, '/') . '/deliveries/status/' . $tripId;

        $response = $this->makeRequest($url, []);

        if ($response->successful()) {
            return $response->json();
        } else {
            if ($this->debugMode) {
                info('------------------- Get Order Error -------------------');
                info('getOrder response: ' . $response->body());
                info('------------------- End Get Order Error -------------------');
            }
            return null;
        }
    }
}
