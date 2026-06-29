<?php

namespace Akika\LaravelWeevo\Support;

use Akika\LaravelWeevo\Support\WeevoCredentials;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeevoClient
{
    protected WeevoCredentials $credentials;

    protected string $environment;

    protected string $baseUrl;

    protected bool $debugMode;

    protected ?string $token = null;

    public function __construct(WeevoCredentials $credentials)
    {
        $this->credentials = $credentials;

        $this->environment = config('weevo.env', 'production');
        $this->baseUrl = rtrim(config("weevo.url.{$this->environment}"), '/');
        $this->debugMode = (bool) config('weevo.debug', false);
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function post(string $endpoint, array $payload = []): Response
    {
        $url = $this->url($endpoint);

        $this->debug('Weevo API Request', [
            'url' => $url,
            'headers' => $this->safeHeaders(),
            'payload' => $payload,
        ]);

        $response = Http::acceptJson()
            ->asJson()
            ->withHeaders($this->headers())
            ->timeout(config('weevo.timeout', 30))
            ->retry(
                config('weevo.retry.times', 2),
                config('weevo.retry.sleep', 500)
            )
            ->withOptions([
                'verify' => config('weevo.verify_ssl', true),
            ])
            ->post($url, $payload);

        $this->debug('Weevo API Response', [
            'url' => $url,
            'status' => $response->status(),
            'body' => $response->json() ?? $response->body(),
        ]);

        return $response;
    }

    public function get(string $endpoint, array $query = []): Response
    {
        $url = $this->url($endpoint);

        $this->debug('Weevo API Request', [
            'url' => $url,
            'headers' => $this->safeHeaders(),
            'query' => $query,
        ]);

        return Http::acceptJson()
            ->withHeaders($this->headers())
            ->timeout(config('weevo.timeout', 30))
            ->retry(
                config('weevo.retry.times', 2),
                config('weevo.retry.sleep', 500)
            )
            ->withOptions([
                'verify' => config('weevo.verify_ssl', true),
            ])
            ->get($url, $query);
    }

    public function getAccessToken(): ?string
    {
        return Cache::remember(
            $this->tokenCacheKey(),
            now()->addSeconds(config('weevo.token_ttl', 3300)),
            function () {
                $response = $this->postWithoutToken('/get-token', [
                    'username' => $this->credentials->username,
                    'api_key' => $this->credentials->apiKey,
                    'api_secret' => $this->credentials->apiSecret,
                ]);

                if (! $response->successful()) {
                    $this->debug('Weevo token request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                return data_get($response->json(), 'token');
            }
        );
    }

    protected function postWithoutToken(string $endpoint, array $payload = []): Response
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout(config('weevo.timeout', 30))
            ->retry(
                config('weevo.retry.times', 2),
                config('weevo.retry.sleep', 500)
            )
            ->withOptions([
                'verify' => config('weevo.verify_ssl', true),
            ])
            ->post($this->url($endpoint), $payload);
    }

    protected function tokenCacheKey(): string
    {
        return sprintf(
            'weevo_token:%s:%s',
            $this->environment,
            $this->credentials->cacheKey()
        );
    }

    protected function url(string $endpoint): string
    {
        return $this->baseUrl . '/' . ltrim($endpoint, '/');
    }

    protected function headers(): array
    {
        $token = $this->token ?: $this->getAccessToken();

        if ($token) {
            return [
                'Authorization' => 'Bearer ' . $token,
            ];
        }

        return [
            'username' => $this->credentials->username,
            'apikey' => $this->credentials->apiKey,
            'apisecret' => $this->credentials->apiSecret,
        ];
    }

    protected function safeHeaders(): array
    {
        $headers = $this->headers();

        foreach (['Authorization', 'apikey', 'apisecret'] as $key) {
            if (isset($headers[$key])) {
                $headers[$key] = '***';
            }
        }

        return $headers;
    }

    public function getUrl(): string
    {
        return $this->baseUrl;
    }

    public function getUsername(): string
    {
        return $this->credentials->username;
    }

    public function getApiKey(): string
    {
        return $this->credentials->apiKey;
    }

    public function getApiSecret(): string
    {
        return $this->credentials->apiSecret;
    }

    public function debug(string $message, array $context = []): void
    {
        if ($this->debugMode) {
            Log::debug($message, $context);
        }
    }
}
