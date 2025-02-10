<?php

/**
 * Gitlab rector
 *
 * @author Vlad Shashkov <v.shashkov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\Rector\CodeQuality;

final readonly class Report
{
    public string $fingerprint;


    /**
     * @param non-empty-string        $description
     * @param non-empty-string        $check_name
     */
    public function __construct(
        public string $description,
        public string $check_name,
        public Severity $severity,
        public ?Location $location = null,
        public ?string $content = null
    ) {

        $this->fingerprint = md5(serialize($this));
    }
}
