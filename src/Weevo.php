<?php

namespace Akika\LaravelWeevo;

use Akika\LaravelWeevo\Traits\WeevoConnect;

class Weevo
{
    public $environment;
    public $debugMode;
    public $url;

    public $username;
    public $apiKey;
    public $apiSecret;
    // Your code here
    use WeevoConnect;

    public function __construct($username, $apiKey, $apiSecret)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->environment = config('weevo.env');
        $this->debugMode = config('weevo.debug');
        $this->url = config('weevo.url');
    }

    public function createDelivery($deliveryData)
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

    public function getDelivery($tripId)
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

    public function getDeliveryStatus($tripId)
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
