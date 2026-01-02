<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * FeatureFlagServiceBundle
 *
 * A Symfony bundle providing a client for feature flag service.
 */
class FeatureFlagServiceBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
