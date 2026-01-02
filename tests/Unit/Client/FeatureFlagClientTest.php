<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagService\Client\FeatureFlagClient;
use Tbessenreither\FeatureFlagService\Client\FeatureFlagClientInterface;

/**
 * Test case for FeatureFlagClient
 */
class FeatureFlagClientTest extends TestCase
{
    private FeatureFlagClient $client;

    protected function setUp(): void
    {
        $this->client = new FeatureFlagClient(
            apiUrl: 'http://localhost:8000',
            apiKey: 'test-key',
            timeout: 10,
            cacheEnabled: true,
            cacheTtl: 600
        );
    }

    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(FeatureFlagClientInterface::class, $this->client);
    }

    public function testConstructorSetsProperties(): void
    {
        $this->assertSame('http://localhost:8000', $this->client->getApiUrl());
        $this->assertSame(10, $this->client->getTimeout());
        $this->assertTrue($this->client->isCacheEnabled());
        $this->assertSame(600, $this->client->getCacheTtl());
    }

    public function testIsEnabledReturnsBoolean(): void
    {
        $result = $this->client->isEnabled('test-flag');
        $this->assertIsBool($result);
    }

    public function testGetValueReturnsValue(): void
    {
        $result = $this->client->getValue('test-flag');
        $this->assertNull($result); // Stub implementation returns null
    }

    public function testGetAllFlagsReturnsArray(): void
    {
        $result = $this->client->getAllFlags();
        $this->assertIsArray($result);
    }
}
