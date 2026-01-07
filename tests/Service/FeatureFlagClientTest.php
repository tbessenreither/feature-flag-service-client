<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Service\FeatureFlagClient;
use Tbessenreither\FeatureFlagServiceClient\Service\FeatureFlagHttpClient;
use Tbessenreither\FeatureFlagServiceClient\Dto\FeatureFlagDto;
use Tbessenreither\FeatureFlagServiceClient\Exception\FeatureFlagException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Log\LoggerInterface;


class FeatureFlagClientTest extends TestCase
{
	private FeatureFlagClient $client;
	private $httpClient;
	private $cache;
	private $logger;

	protected function setUp(): void
	{
		$this->httpClient = $this->createMock(FeatureFlagHttpClient::class);
		$this->cache = $this->createMock(CacheInterface::class);
		$this->logger = $this->createMock(LoggerInterface::class);
		$this->client = new FeatureFlagClient(
			$this->httpClient,
			$this->logger,
			$this->cache,
			300
		);
	}

	public function testImplementsInterface(): void
	{
		$this->assertInstanceOf(FeatureFlagClient::class, $this->client);
	}

	public function testReturnsCachedFlag(): void
	{
		$this->cache->method('get')->willReturn(true);
		$result = $this->client->isEnabled('server.module.feature');
		$this->assertTrue($result);
	}

	public function testFetchesFlagFromApiIfNotCached(): void
	{
		$flagDto = new FeatureFlagDto('default', 'server.module.feature', 'true', true, null);
		$this->cache->method('get')->willReturnCallback(function ($key, $callback) use ($flagDto) {
			return $callback($this->createMock(ItemInterface::class));
		});
		$this->httpClient->method('fetchOne')->willReturn($flagDto);
		$result = $this->client->isEnabled('server.module.feature');
		$this->assertTrue($result);
	}

	public function testReturnsFallbackIfApiFails(): void
	{
		$this->cache->method('get')->willThrowException(new \Exception('Cache error'));
		$result = $this->client->isEnabled('server.module.feature', false);
		$this->assertFalse($result);
	}

	public function testThrowsExceptionIfNoFallback(): void
	{
		$this->cache->method('get')->willThrowException(new \Exception('Cache error'));
		$this->expectException(FeatureFlagException::class);
		$this->client->isEnabled('server.module.feature');
	}

	// Add more tests for error logging, cache expiry, etc. as needed

}
