<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Service;

use Psr\Log\LoggerInterface;
use Tbessenreither\FeatureFlagServiceClient\Exception\FeatureFlagException;
use Tbessenreither\FeatureFlagServiceClient\Interface\FeatureFlagClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Throwable;


class FeatureFlagClient implements FeatureFlagClientInterface
{
	private string $cachePrefix = 'feature_flag_service:flag:';

	public function __construct(
		private FeatureFlagHttpClient $featureFlagHttpClient,
		private readonly LoggerInterface $logger,
		private CacheInterface $cache,
		private readonly int $cacheTtl = 300,
	) {
	}

	public function lookup(string $keyPath, ?bool $fallback = null): bool
	{
		try {
			return $this->getCachedFlag($keyPath);
		} catch (Throwable $e) {
			$this->logger->error('Error looking up feature flag "' . $keyPath . '": ' . $e->getMessage(), [
				'keyPath' => $keyPath,
				'message' => $e->getMessage(),
				'fallback' => $fallback,
			]);

			if ($fallback !== null) {
				return $fallback;
			}

			throw new FeatureFlagException(
				message: 'Failed to lookup feature flag ' . $keyPath . ' and no fallback was provided.',
				previous: $e,
			);
		}
	}

	private function getCachedFlag(string $keyPath): bool
	{
		return $this->cache->get(
			$this->cachePrefix . $keyPath,
			function (ItemInterface $item) use ($keyPath) {
				$item->expiresAfter($this->cacheTtl);
				$flag = $this->featureFlagHttpClient->fetchOne($keyPath);

				return $flag->isEnabled();
			},
			1.0,
		);
	}

}
