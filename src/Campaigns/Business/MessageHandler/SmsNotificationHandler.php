<?php

declare(strict_types=1);

namespace App\Campaigns\Business\MessageHandler;

use App\Campaigns\Business\Message\SmsNotification;
use App\Campaigns\Business\SMS\SmsClientInterface;
use App\Campaigns\Persistence\Entity\Campaign;
use App\Campaigns\Persistence\Repository\CampaignRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final class SmsNotificationHandler
{
    private const MAXIMUM_RECIPIENTS_PER_REQUEST = 999;

    public function __construct(
        private readonly CampaignRepository $campaignRepository,
        private readonly SmsClientInterface $smsClient,
        private readonly CacheInterface $cache
    ) {
    }

    public function __invoke(SmsNotification $campaign): void
    {
        $campaign = $this->campaignRepository->find($campaign->getCampaignId());

        Assert::isInstanceOf($campaign, Campaign::class);

        $campaignId = $campaign->getId();
        $campaignMessage = $campaign->getMessage();
        $campaignRecipients = $campaign->getContacts();

        $campaignRecipientsChunks = array_chunk($campaignRecipients, self::MAXIMUM_RECIPIENTS_PER_REQUEST);

        array_map(function ($recipients) use ($campaignId, $campaignMessage) {
            //            $notNotifiedRecipients = $this->cache->get('recipients', function (ItemInterface $item) use ($recipients): array {
            //                $item->expiresAfter(3600 * 24);
            //
            //                $recipientsPhoneNumbers = array_column($recipients, 'phone_number');
            //
            //                return  $item->get() !== null ? array_diff($recipientsPhoneNumbers, $item->get()) : $recipientsPhoneNumbers;
            //            });

            $this->smsClient->bulk($campaignId, $campaignMessage, $recipients);
        }, $campaignRecipientsChunks);
    }
}
