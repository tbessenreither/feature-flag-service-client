<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Tests\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagService\Service\FeatureFlagHttpClient;
use Tbessenreither\FeatureFlagService\Dto\FeatureFlagDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


class FeatureFlagHttpClientTest extends TestCase
{

	public function testFetchAllReturnsDtos(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn(json_encode([
			['scope' => 'default', 'key' => 'flag1', 'value' => 'true', 'enabled' => true],
			['scope' => 'default', 'key' => 'flag2', 'value' => 'false', 'enabled' => false],
		]));
		$response->method('getStatusCode')->willReturn(200);
		$httpClient->method('request')->willReturn($response);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient);
		$result = $client->fetchAll();
		$this->assertIsArray($result);
		$this->assertInstanceOf(FeatureFlagDto::class, $result[0]);
		$this->assertTrue($result[0]->isEnabled());
		$this->assertFalse($result[1]->isEnabled());
	}

	public function testFetchOneReturnsDto(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn(json_encode([
			'scope' => 'default',
			'key' => 'flag1',
			'value' => 'true',
			'enabled' => true,
		]));
		$response->method('getStatusCode')->willReturn(200);
		$httpClient->method('request')->willReturn($response);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient);
		$result = $client->fetchOne('flag1');
		$this->assertInstanceOf(FeatureFlagDto::class, $result);
		$this->assertTrue($result->isEnabled());
		$this->assertSame('flag1', $result->getKey());
	}

	public function testFetchOneThrowsOnInvalidResponse(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn('not a json array');
		$httpClient->method('request')->willReturn($response);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient);
		$this->expectException(Exception::class);
		$client->fetchOne('flag1');
	}

	public function testFetchAllThrowsOnInvalidResponse(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn('not a json array');
		$httpClient->method('request')->willReturn($response);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient);
		$this->expectException(Exception::class);
		$client->fetchAll();
	}

}
