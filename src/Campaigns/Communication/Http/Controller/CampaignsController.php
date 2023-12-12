<?php

declare(strict_types=1);

namespace App\Campaigns\Communication\Http\Controller;

use App\Campaigns\Business\Actions\PrepareContactsAction;
use App\Campaigns\Business\Actions\SerializeCampaignAction;
use App\Campaigns\Business\Message\SmsNotification;
use App\Campaigns\Persistence\Repository\CampaignRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CampaignsController extends AbstractController
{
    public function __construct(
        private readonly CampaignRepository $campaignRepository,
        private readonly MessageBusInterface $bus,
        private readonly SerializeCampaignAction $serializeCampaignAction,
        private readonly PrepareContactsAction $prepareContactsAction,
    ) {
    }

    #[Route('/campaigns', name: 'campaigns.list')]
    public function index(): JsonResponse
    {
        $campaigns = $this->campaignRepository->findAll();

        $jsonContent = ($this->serializeCampaignAction)($campaigns);

        return $this->json($jsonContent);
    }

    #[Route('/campaigns/store', name: 'campaigns.store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        try {
            $message = $request->get('message');

            $contacts = ($this->prepareContactsAction)($request->files->get('contacts'));

            $campaign = $this->campaignRepository->add($message, $contacts);

            $this->bus->dispatch(new SmsNotification($campaign->getId()));

            return $this->json([
                'message' => 'Campaign has been created successfully',
            ]);
        } catch (\Exception $exception) {
            return $this->json([
                'errorMessage' => $exception->getMessage(),
            ]);
        }
    }
}
