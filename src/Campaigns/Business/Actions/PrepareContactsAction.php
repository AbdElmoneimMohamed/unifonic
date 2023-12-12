<?php

declare(strict_types=1);

namespace App\Campaigns\Business\Actions;

use Illuminate\Support\Collection;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Webmozart\Assert\Assert;

final class PrepareContactsAction
{
    private const PHONE_NUMBER_INDEX = 2;

    /**
     * @return array<int, array{email: string, last_name: string, first_name: string, phone_number: string}>
     * @throws \League\Csv\Exception
     */
    public function __invoke(UploadedFile $contactsFileContent): array
    {
        $content = file_get_contents($contactsFileContent->getPathname());

        Assert::string($content);

        $contacts = Reader::createFromString($content)->jsonSerialize();

        $keys = $contacts[0];

        return Collection::make($contacts)
            ->skip(1)
            ->unique()
            ->filter(fn ($contact) => ! empty($contact[self::PHONE_NUMBER_INDEX]))
            ->map(fn ($contact) => array_combine($keys, $contact))
            ->toArray();
    }
}
