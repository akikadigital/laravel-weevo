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

    public string $apiToken = '';

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

        if ($this->debugMode) {
            info('------------------- End Set Credentials -------------------');
        }
    }

    public function setToken($token)
    {
        $this->apiToken = $token;

        if ($this->debugMode) {
            info('------------------- Set Token -------------------');
            info('Token: ' . $this->apiToken);
        }
    }

    public function authenticate(): ?array
    {
        // For demonstration, we assume authentication is successful if credentials are set
        if ($this->username && $this->apiKey && $this->apiSecret) {
            $url = rtrim($this->url, '/') . '/get-token';

            $response = $this->makeRequest($url, [
                'username' => $this->username,
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret
            ]);

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
        } else {
            if ($this->debugMode) {
                info('------------------- Authentication Failed -------------------');
            }
            return null;
        }
    }

    public function createDelivery(array $deliveryData): ?array
    {
        $url = rtrim($this->url, '/') . '/create-delivery';

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
