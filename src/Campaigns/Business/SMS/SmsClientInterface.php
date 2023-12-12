<?php

declare(strict_types=1);

namespace App\Campaigns\Business\SMS;

interface SmsClientInterface
{
    public function send(string $message, string $phoneNumber): void;

    /**
     * @param array<int, mixed> $recipients
     */
    public function bulk(int $campaignId, string $message, array $recipients): void;
}
