<?php

namespace Akika\LaravelWeevo\Support;

readonly class WeevoCredentials
{
    public function __construct(
        public string $username,
        public string $apiKey,
        public string $apiSecret,
    ) {}
}