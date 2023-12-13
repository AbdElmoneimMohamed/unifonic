<?php

declare(strict_types=1);

namespace App\Campaigns\Business\MessageHandler;

use App\Campaigns\Business\Actions\NotifiedRecipientsAction;
use App\Campaigns\Business\Message\SmsNotification;
use App\Campaigns\Business\SMS\SmsClientInterface;
use App\Campaigns\Persistence\Entity\Campaign;
use App\Campaigns\Persistence\Repository\CampaignRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final readonly class SmsNotificationHandler
{
    private const MAXIMUM_RECIPIENTS_PER_REQUEST = 999;

    public function __construct(
        private CampaignRepository $campaignRepository,
        private SmsClientInterface $smsClient,
        private NotifiedRecipientsAction $recipientsAction
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
            $notNotifiedRecipients = ($this->recipientsAction)($recipients);

            if (count($notNotifiedRecipients) !== 0) {
                $notNotifiedRecipients = array_map(function ($recipient) {
                    return array_combine(['phone_number'], [$recipient]);
                }, $notNotifiedRecipients);

                $this->smsClient->bulk($campaignId, $campaignMessage, array_values($notNotifiedRecipients));
            }
        }, $campaignRecipientsChunks);
    }
}
