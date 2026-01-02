<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Service;

use Tbessenreither\FeatureFlagService\Exception\FeatureFlagException;
use Tbessenreither\FeatureFlagService\Interface\FeatureFlagClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;


/**
 * Feature Flag Client
 *
 * This is a basic implementation of the feature flag client.
 * It can be extended to add actual API communication logic.
 */
class FeatureFlagClient implements FeatureFlagClientInterface
{
	private string $cachePrefix = 'ffs_flag_';

	public function __construct(
		private readonly string $ffsApiUrl,
		private readonly string $ffsScope,
		private readonly string $ffsApiKey,
		private readonly int $timeout = 5,
		private ?CacheInterface $cache = null,
		private readonly int $cacheTtl = 300,
	) {
	}

	public function lookup(string $keyPath, ?bool $default = null): bool
	{
		$cached = $this->getCachedFlag($keyPath);
		if ($cached !== null) {
			return $cached;
		}
		$preloaded = $this->preloadAllFlags();
		if (is_array($preloaded)) {
			$this->cacheAllFlags($preloaded);
			$cached = $this->getCachedFlag($keyPath);
			if ($cached !== null) {
				return $cached;
			}
		}
		$flag = $this->fetchFlag($keyPath);
		if (is_array($flag) && isset($flag['value'])) {
			$boolVal = $flag['value'] === 'true';
			$this->cacheFlag($keyPath, $boolVal);

			return $boolVal;
		}
		if ($default !== null) {
			return $default;
		}
		throw new FeatureFlagException("Feature flag '$keyPath' could not be loaded and no default provided.");
	}

	private function getCachedFlag(string $keyPath): ?bool
	{
		if (!$this->isCacheAvailable()) {
			return null;
		}
		return $this->cache->get($this->cachePrefix . $keyPath, function (ItemInterface $item) {
			$item->expiresAfter($this->cacheTtl);
			return null;
		});
	}

	private function cacheAllFlags(array $flags): void
	{
		if (!$this->isCacheAvailable()) {
			return;
		}
		foreach ($flags as $flag) {
			if (isset($flag['keyPath'], $flag['value'])) {
				$this->cacheFlag($flag['keyPath'], $flag['value'] === 'true');
			}
		}
	}

	private function cacheFlag(string $keyPath, bool $value): void
	{
		if (!$this->isCacheAvailable()) {
			return;
		}
		$this->cache->get($this->cachePrefix . $keyPath, function (ItemInterface $item) use ($value) {
			$item->expiresAfter($this->cacheTtl);
			return $value;
		});
	}

	private function isCacheAvailable(): bool
	{
		return $this->cache !== null;
	}

	private function getHeaders(): array
	{
		return [
			'Accept: application/json',
			'Content-Type: application/json',
			'X-API-Key: ' . $this->ffsApiKey,
		];
	}

	private function httpGet(string $url): ?array
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpCode >= 200 && $httpCode < 300 && $response !== false) {
			return json_decode($response, true);
		}
		return null;
	}

	private function preloadAllFlags(): ?array
	{
		$url = rtrim($this->ffsApiUrl, '/') . '/api/scope/' . urlencode($this->ffsScope) . '/feature_flags';
		return $this->httpGet($url);
	}

	private function fetchFlag(string $keyPath): ?array
	{
		$url = rtrim($this->ffsApiUrl, '/') . '/api/scope/' . urlencode($this->ffsScope) . '/feature_flag/' . urlencode($keyPath);
		return $this->httpGet($url);
	}

}
