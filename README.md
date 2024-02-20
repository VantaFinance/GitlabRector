# Gitlab rector

## Install

1. call command ```composer require --dev vanta/gitlab-rector```
2. edit config rector.php and add GitlabOutputFormatter


### Config if rector >= 1.0.1
```php
<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;
use Vanta\Integration\Rector\GitlabOutputFormatter;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;

return RectorConfig::configure()
    ->withCache(
        cacheClass: FileCacheStorage::class,
        cacheDirectory: 'var'
    )
    ->withTypeCoverageLevel(10)
    ->withDeadCodeLevel(10)
    ->withPreparedSets(codeQuality: true, codingStyle: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->registerService(GitlabOutputFormatter::class, 'gitlab', OutputFormatterInterface::class)

```


### Config if rector >=0.18


```php
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
```



### Config if rector <0.18


```php
<?php

declare(strict_types=1);

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

    $config->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()

        ->set(GitlabOutputFormatter::class)
    ;

    $config->rules([
        ChangeNestedIfsToEarlyReturnRector::class,
        RemoveAlwaysElseRector::class,
    ]);


    $config->sets([
        SetList::CODE_QUALITY,
    ]);
}
```



## Usage in ci:

```yaml
lint-rector:
  stage: lint
  script:
    - vendor/bin/rector --dry-run --output-format=gitlab > rector-report.json
  artifacts:
    reports:
      codequality: rector-report.json
    when: always
```
