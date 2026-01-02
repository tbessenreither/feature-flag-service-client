<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Dto\FeatureFlagDto;


class FeatureFlagDtoTest extends TestCase
{

	public function testDtoProperties(): void
	{
		$dto = new FeatureFlagDto('scope', 'key', 'value', true);
		$this->assertSame('scope', $dto->getScope());
		$this->assertSame('key', $dto->getKey());
		$this->assertSame('value', $dto->getValue());
		$this->assertTrue($dto->isEnabled());

		$dtoFalse = new FeatureFlagDto('scope2', 'key2', 'value2', false);
		$this->assertSame('scope2', $dtoFalse->getScope());
		$this->assertSame('key2', $dtoFalse->getKey());
		$this->assertSame('value2', $dtoFalse->getValue());
		$this->assertFalse($dtoFalse->isEnabled());
	}

}
