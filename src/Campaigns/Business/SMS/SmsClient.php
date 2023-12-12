<?php

declare(strict_types=1);

namespace App\Campaigns\Business\SMS;

use GuzzleHttp\ClientInterface;

final class SmsClient implements SmsClientInterface
{
    public function __construct(
        private readonly ClientInterface $smsHttpClient
    ) {
    }

    public function send(string $message, string $phoneNumber): void
    {
        try {
            $this->smsHttpClient->request(
                'POST',
                '/send',
                [
                    'json' => [
                        'message' => $message,
                        'phone_number' => $phoneNumber,
                    ],
                ]
            );
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * @param array<int, mixed> $recipients
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function bulk(int $campaignId, string $message, array $recipients): void
    {
        try {
            $this->smsHttpClient->request(
                'POST',
                '/bulk',
                [
                    'json' => [
                        'campaign_id' => $campaignId,
                        'message' => $message,
                        'recipients' => $recipients,
                    ],
                ]
            );
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
