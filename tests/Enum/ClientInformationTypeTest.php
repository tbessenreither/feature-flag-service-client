<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;


class ClientInformationTypeTest extends TestCase
{

	public function testEnumCases(): void
	{
		$this->assertSame('information', ClientInformationType::Information->value);
		$this->assertSame('warning', ClientInformationType::Warning->value);
		$this->assertSame('error', ClientInformationType::Error->value);
	}

}
