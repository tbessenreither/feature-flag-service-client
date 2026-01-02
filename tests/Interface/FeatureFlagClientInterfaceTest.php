<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Tests\Interface;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tbessenreither\FeatureFlagService\Interface\FeatureFlagClientInterface;


class FeatureFlagClientInterfaceTest extends TestCase
{

	public function testInterfaceHasLookupMethod(): void
	{
		$reflection = new ReflectionClass(FeatureFlagClientInterface::class);
		$this->assertTrue($reflection->hasMethod('lookup'));
	}

}
