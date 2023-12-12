<?php

declare(strict_types=1);

namespace App\Campaigns\Business\SMS\Factory;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

final readonly class SmsHttpClientFactory
{
    public function __construct(
        private string $baseUrl
    ) {
    }

    public function __invoke(): ClientInterface
    {
        return new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }
}
