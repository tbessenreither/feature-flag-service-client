<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Service;

use Exception;
use PHPUnit\Framework\TestCase;

use Tbessenreither\FeatureFlagServiceClient\Service\FeatureFlagHttpClient;
use Tbessenreither\FeatureFlagServiceClient\Dto\FeatureFlagDto;
use Tbessenreither\FeatureFlagServiceClient\Dto\ClientInformationDto;
use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;


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
		$logger = $this->createMock(LoggerInterface::class);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient, 5, $logger);
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
		$logger = $this->createMock(LoggerInterface::class);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient, 5, $logger);
		$result = $client->fetchOne('flag1');
		$this->assertInstanceOf(FeatureFlagDto::class, $result);
		$this->assertTrue($result->isEnabled());
		$this->assertSame('flag1', $result->getKey());
		$this->assertNull($result->getClientInformation(), 'Client information should be null when not provided.');
	}

	public function testFetchOneThrowsOnInvalidResponse(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn('not a json array');
		$httpClient->method('request')->willReturn($response);
		$logger = $this->createMock(LoggerInterface::class);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient, 5, $logger);
		$this->expectException(Exception::class);
		$client->fetchOne('flag1');
	}

	public function testFetchAllThrowsOnInvalidResponse(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn('not a json array');
		$httpClient->method('request')->willReturn($response);
		$logger = $this->createMock(LoggerInterface::class);
		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient, 5, $logger);
		$this->expectException(Exception::class);
		$client->fetchAll();
	}

	public function testFetchOneWithClientInformationLogs(): void
	{
		$httpClient = $this->createMock(HttpClientInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$response->method('getContent')->willReturn(json_encode([
			'scope' => 'default',
			'key' => 'flag1',
			'value' => 'true',
			'enabled' => true,
			'clientInformation' => [
				'type' => ClientInformationType::Warning->value,
				'message' => 'This is a warning',
			],
		]));
		$response->method('getStatusCode')->willReturn(200);
		$httpClient->method('request')->willReturn($response);

		$logger = $this->createMock(LoggerInterface::class);
		$logger->expects($this->never())->method('error');
		$logger->expects($this->once())->method('warning')->with(
			$this->stringContains('has client information'),
			$this->arrayHasKey('keyPath')
		);
		$logger->expects($this->never())->method('info');

		$client = new FeatureFlagHttpClient('http://api', 'default', 'api-key', $httpClient, 5, $logger);
		$result = $client->fetchOne('flag1');
		$this->assertInstanceOf(FeatureFlagDto::class, $result);
		$this->assertInstanceOf(ClientInformationDto::class, $result->getClientInformation());
		$this->assertSame(ClientInformationType::Warning, $result->getClientInformation()->getType());
		$this->assertSame('This is a warning', $result->getClientInformation()->getMessage());
	}

}
