<?php

namespace Akika\LaravelWeevo;

use Akika\LaravelWeevo\Traits\WeevoConnect;

class Weevo
{
    public $environment;
    public $debugMode;
    public $url;

    public $apiKey;
    public $apiSecret;
    // Your code here
    use WeevoConnect;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->environment = config('weevo.env');
        $this->debugMode = config('weevo.debug');
        $this->url = config('weevo.' . $this->environment . '.url');
    }

    public function getToken()
    {
        $url = $this->url . '/token';
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'scope' => 'api'
        ];

        $response = $this->makeAuthRequest($this->apiKey, $url, $body, 'POST');

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'];
        } else {
            if ($this->debugMode) {
                info('------------------- Get Token Error -------------------');
                info('getToken response: ' . $response->body());
                info('------------------- End Get Token Error -------------------');
            }
            return null;
        }
    }

    public function createOrder($apiToken, $orderData)
    {
        $url = $this->url . '/orders/create';
        $body = $orderData;

        $response = $this->makeRequest($this->apiKey, $apiToken, $url, $body, 'POST');

        if ($response->successful()) {
            return $response->json();
        } else {
            if ($this->debugMode) {
                info('------------------- Create Order Error -------------------');
                info('createOrder response: ' . $response->body());
                info('------------------- End Create Order Error -------------------');
            }
            return null;
        }
    }

    public function getOrder($apiToken, $orderId)
    {
        $url = $this->url . '/orders/show/' . $orderId;

        $response = $this->makeRequest($this->apiKey, $apiToken, $url, [], 'GET');

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

    public function getOrderStatus($apiToken, $orderId)
    {
        $url = $this->url . '/orders/status/' . $orderId;

        $response = $this->makeRequest($this->apiKey, $apiToken, $url, [], 'GET');

        if ($response->successful()) {
            return $response->json();
        } else {
            if ($this->debugMode) {
                info('------------------- Get Order Status Error -------------------');
                info('getOrderStatus response: ' . $response->body());
                info('------------------- End Get Order Status Error -------------------');
            }
            return null;
        }
    }
}
