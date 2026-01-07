<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Dto\FeatureFlagDto;
use Tbessenreither\FeatureFlagServiceClient\Dto\ClientInformationDto;
use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;


class FeatureFlagDtoTest extends TestCase
{

	public function testDtoProperties(): void
	{
		$info = new ClientInformationDto(ClientInformationType::Information, 'info');
		$dto = new FeatureFlagDto('scope', 'key', 'value', true, $info);
		$this->assertSame('scope', $dto->getScope());
		$this->assertSame('key', $dto->getKey());
		$this->assertSame('value', $dto->getValue());
		$this->assertTrue($dto->isEnabled());
		$this->assertSame($info, $dto->getClientInformation());

		$dtoFalse = new FeatureFlagDto('scope2', 'key2', 'value2', false, null);
		$this->assertSame('scope2', $dtoFalse->getScope());
		$this->assertSame('key2', $dtoFalse->getKey());
		$this->assertSame('value2', $dtoFalse->getValue());
		$this->assertFalse($dtoFalse->isEnabled());
		$this->assertNull($dtoFalse->getClientInformation());
	}

}
