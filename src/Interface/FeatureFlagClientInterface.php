<?php
declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Interface;


/**
 * Interface for feature flag client
 */
interface FeatureFlagClientInterface
{

	/**
	 * Lookup a feature flag value with fallback.
	 *
	 * @param string $keyPath
	 * @param ?bool $fallback
	 * @return bool
	 */
	public function isEnabled(string $keyPath, ?bool $fallback = null): bool;

}
