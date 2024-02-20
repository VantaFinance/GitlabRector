<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Vanta\Integration\Rector\GitlabOutputFormatter;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;

return RectorConfig::configure()
    ->withCache(
        cacheDirectory: 'var',
        cacheClass: FileCacheStorage::class
    )
    ->withTypeCoverageLevel(10)
    ->withDeadCodeLevel(10)
    ->withPreparedSets(codeQuality: true, codingStyle: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withPaths([
        __DIR__ . '/fixture',
    ])
    ->withRules([
        ChangeNestedIfsToEarlyReturnRector::class,
        RemoveAlwaysElseRector::class,
    ])
    ->registerService(GitlabOutputFormatter::class, 'gitlab', OutputFormatterInterface::class)
;
