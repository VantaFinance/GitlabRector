<?php

declare(strict_types=1);

namespace Vanta\Integration\Rector\CodeQuality;

final readonly class Location
{
    public function __construct(
        public string $path,
        public ?Line $lines = null,
    ) {
    }
}
