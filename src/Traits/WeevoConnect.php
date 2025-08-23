<?php

namespace Akika\LaravelWeevo\Traits;

use Illuminate\Support\Facades\Http;

trait WeevoConnect
{
    /**
     * Make an authenticated request to the Weevo API.
     *
     * @param string $apiKey
     * @param string $url
     * @param array $body
     * @param string $method
     * @return mixed
     */
    private function makeAuthRequest($apiKey, $url, $body, $method = 'POST')
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => $apiKey,
            'Content-Type' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->acceptJson();

        if ($this->debugMode) {
            info('------------------- Make Request -------------------');
            info('makeRequest url: ' . $url);
            info('makeRequest headers: ' . json_encode($headers));
            info('makeRequest data: ' . json_encode($body));
            info('------------------- End Make Request -------------------');
        }

        if ($method == 'GET') {
            $response = $response->get($url, $body);
        } else {
            $response = $response->post($url, $body);
        }

        return $response;
    }

    private function makeRequest($apiKey, $apiToken, $url, $body, $method = 'POST')
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => $apiKey,
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
        ];

        $response = Http::withHeaders($headers)->acceptJson();

        if ($this->debugMode) {
            info('------------------- Make Request -------------------');
            info('makeRequest url: ' . $url);
            info('makeRequest headers: ' . json_encode($headers));
            info('makeRequest data: ' . json_encode($body));
            info('------------------- End Make Request -------------------');
        }

        if ($method == 'GET') {
            $response = $response->get($url, $body);
        } else {
            $response = $response->post($url, $body);
        }

        return $response;
    }
}