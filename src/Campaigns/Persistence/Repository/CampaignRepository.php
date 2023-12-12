<?php

declare(strict_types=1);

namespace App\Campaigns\Persistence\Repository;

use App\Campaigns\Persistence\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campaign>
 *
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($registry, Campaign::class);
    }

    /**
     * @param array<int, array{email:string, last_name: string, first_name:string, phone_number:string}> $contacts
     */
    public function add(string $message, array $contacts): Campaign
    {
        $campaign = new Campaign();

        $campaign->setMessage($message);
        $campaign->setContacts(array_values($contacts));
        $campaign->setCreatedAT(new \DateTimeImmutable('now'));

        $this->entityManager->persist($campaign);

        $this->entityManager->flush();

        return $campaign;
    }
}
