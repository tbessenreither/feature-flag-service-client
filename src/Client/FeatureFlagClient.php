<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Client;

/**
 * Feature Flag Client
 *
 * This is a basic implementation of the feature flag client.
 * It can be extended to add actual API communication logic.
 */
class FeatureFlagClient implements FeatureFlagClientInterface
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly ?string $apiKey = null,
        private readonly int $timeout = 5,
        private readonly bool $cacheEnabled = true,
        private readonly int $cacheTtl = 300,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(string $flagName, array $context = []): bool
    {
        // TODO: Implement actual API call to feature flag service
        // This is a stub that always returns false
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(string $flagName, array $context = []): mixed
    {
        // TODO: Implement actual API call to feature flag service
        // This is a stub that returns null
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllFlags(): array
    {
        // TODO: Implement actual API call to feature flag service
        // This is a stub that returns an empty array
        return [];
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }
}
