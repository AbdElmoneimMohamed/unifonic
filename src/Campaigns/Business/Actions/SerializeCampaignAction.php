<?php

declare(strict_types=1);

namespace App\Campaigns\Business\Actions;

use App\Campaigns\Persistence\Entity\Campaign;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Webmozart\Assert\Assert;

final readonly class SerializeCampaignAction
{
    /**
     * @param array<Campaign> $campaigns
     * @return array<string, string>
     */
    public function __invoke(array $campaigns): array
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $campaigns = $serializer->normalize($campaigns);

        Assert::isArray($campaigns);

        return $campaigns;
    }
}
