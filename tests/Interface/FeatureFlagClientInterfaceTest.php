<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Interface;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tbessenreither\FeatureFlagServiceClient\Interface\FeatureFlagClientInterface;


class FeatureFlagClientInterfaceTest extends TestCase
{

	public function testInterfaceHasLookupMethod(): void
	{
		$reflection = new ReflectionClass(FeatureFlagClientInterface::class);
		$this->assertTrue($reflection->hasMethod('lookup'));
	}

}
