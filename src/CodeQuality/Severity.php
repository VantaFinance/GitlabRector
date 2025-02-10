<?php

/**
 * Gitlab rector
 *
 * @author Vlad Shashkov <v.shashkov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\Rector\CodeQuality;

use JsonSerializable;

enum Severity: string implements JsonSerializable
{
    case INFO     = 'info';
    case MAJOR    = 'major';
    case MINOR    = 'minor';
    case CRITICAL = 'critical';
    case BLOCKER  = 'blocker';
    case UNKNOWN  = 'unknown';


    /**
     * @return non-empty-string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
