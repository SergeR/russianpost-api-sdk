<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK;

final readonly class Config
{
    public function __construct(
        public string $accessToken,
        public string $login,
        public string $password,
        public string $baseUrl = 'https://otpravka-api.pochta.ru',
        public int $timeout = 30,
    ) {}

    public function userAuthHeader(): string
    {
        return 'Basic ' . base64_encode($this->login . ':' . $this->password);
    }
}
