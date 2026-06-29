<?php

namespace Akika\LaravelWeevo\Support;

readonly class WeevoCredentials
{
    public function __construct(
        public string $username,
        public string $apiKey,
        public string $apiSecret,
    ) {}

    public function cacheKey(): string
    {
        return sprintf(
            '%s:%s',
            $this->username,
            md5($this->apiKey)
        );
    }
}

