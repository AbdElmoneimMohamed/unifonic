<?php

declare(strict_types=1);

namespace App\Campaigns\Business\Message;

final class SmsNotification
{
    public function __construct(
        private readonly int $campaignId
    ) {
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}
