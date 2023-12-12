<?php

declare(strict_types=1);

namespace App\Campaigns\Persistence\Entity;

use App\Campaigns\Persistence\Repository\CampaignRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
#[ORM\Table('campaigns')]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $message;

    /**
     * @var array<int, array{email:string, last_name: string, first_name:string, phone_number:string}>
     */
    #[ORM\Column(type: 'json')]
    private array $contacts = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTimeInterface $Created_AT;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array<int, array{email:string, last_name: string, first_name:string, phone_number:string}>
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @param array<int, array{email:string, last_name: string, first_name:string, phone_number:string}> $contacts
     * @return $this
     */
    public function setContacts(array $contacts): static
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getCreatedAT(): DateTimeInterface
    {
        return $this->Created_AT;
    }

    public function setCreatedAT(DateTimeInterface $Created_AT): static
    {
        $this->Created_AT = $Created_AT;

        return $this;
    }
}
