<?php
declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Interface;


/**
 * Interface for feature flag client
 */
interface FeatureFlagClientInterface
{

	/**
	 * Lookup a feature flag value with fallback and error logic.
	 *
	 * @param string $keyPath
	 * @param ?bool $default
	 * @return bool
	 */
	public function lookup(string $keyPath, ?bool $default = null): bool;

}
