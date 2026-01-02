<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Exception\FeatureFlagException;


class FeatureFlagExceptionTest extends TestCase
{

	public function testExceptionMessage(): void
	{
		$e = new FeatureFlagException('fail', 0, null);
		$this->assertSame('fail', $e->getMessage());
	}

}
