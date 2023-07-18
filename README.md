# Gitlab rector

## Install

1. call command ```composer require --dev vanta/gitlab-rector```
2. edit config rector.php and add GitlabOutputFormatter

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
};
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
