<?php

namespace Akika\LaravelWeevo\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait WeevoConnect
{

    private function makeRequest(string $url, array $body): Response
    {
        $headers = [
            'Content-Type' => 'application/json',
            'username' => $this->username,
            'apikey' => $this->apiKey,
            'apisecret' => $this->apiSecret
        ];

        $response = Http::withHeaders($headers)->acceptJson();

        if ($this->debugMode) {
            info('------------------- Make Request -------------------');
            info('makeRequest url: ' . $url);
            info('makeRequest headers: ' . json_encode($headers));
            info('makeRequest data: ' . json_encode($body));
            info('------------------- End Make Request -------------------');
        }

        return $response->post($url, $body);
    }
}
