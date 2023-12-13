<?php

declare(strict_types=1);

namespace App\Campaigns\Business\Actions;

use Psr\Cache\CacheItemPoolInterface;

final readonly class NotifiedRecipientsAction
{
    public function __construct(
        private CacheItemPoolInterface $cache
    ) {
    }

    /**
     * @param array<int, array{email:string, last_name: string, first_name:string, phone_number:string}> $recipients
     * @return array<string>
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __invoke(array $recipients): array
    {
        $notifiedRecipients = $this->cache->getItem('recipients');

        $recipientsPhoneNumbers = array_column($recipients, 'phone_number');

        if (! $notifiedRecipients->isHit()) {
            $this->cache->save(
                $notifiedRecipients->set($recipientsPhoneNumbers)->expiresAfter(3600 * 24)
            );

            $notNotifiedRecipients = $recipientsPhoneNumbers;
        } else {
            $notNotifiedRecipients = array_diff($recipientsPhoneNumbers, $notifiedRecipients->get());

            $this->cache->save(
                $notifiedRecipients->set(array_merge($notifiedRecipients->get(), $notNotifiedRecipients))
            );
        }

        return $notNotifiedRecipients;
    }
}
