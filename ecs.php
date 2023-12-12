<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $containerConfigurator): void {
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::PSR_12);

    $containerConfigurator->parallel();
    $containerConfigurator->paths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/ecs.php',
    ]);
};
