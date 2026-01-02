<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Client;

/**
 * Interface for feature flag client
 */
interface FeatureFlagClientInterface
{
    /**
     * Check if a feature flag is enabled
     *
     * @param string $flagName The name of the feature flag
     * @param array<string, mixed> $context Additional context for evaluation
     * @return bool True if the feature is enabled, false otherwise
     */
    public function isEnabled(string $flagName, array $context = []): bool;

    /**
     * Get the value of a feature flag
     *
     * @param string $flagName The name of the feature flag
     * @param array<string, mixed> $context Additional context for evaluation
     * @return mixed The feature flag value
     */
    public function getValue(string $flagName, array $context = []): mixed;

    /**
     * Get all feature flags
     *
     * @return array<string, mixed> All feature flags
     */
    public function getAllFlags(): array;
}
