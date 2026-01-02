<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Exception;

use RuntimeException;


/**
 * Exception thrown when a feature flag cannot be loaded and no default is provided.
 */
class FeatureFlagException extends RuntimeException
{

}
