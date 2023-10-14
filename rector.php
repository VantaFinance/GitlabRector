<?php

declare(strict_types=1);

use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\Set\ValueObject\SetList;
use Vanta\Integration\Rector\GitlabOutputFormatter;

return static function (RectorConfig $config): void {
    $config->paths([
        __DIR__ . '/fixture',
    ]);

    $config->cacheDirectory('var');

    $config->bind(GitlabOutputFormatter::class);
    $config->tag(GitlabOutputFormatter::class, [OutputFormatterInterface::class]);

    $config->rules([
        ChangeNestedIfsToEarlyReturnRector::class,
        RemoveAlwaysElseRector::class,
    ]);


    $config->sets([
        SetList::CODE_QUALITY,
    ]);
};