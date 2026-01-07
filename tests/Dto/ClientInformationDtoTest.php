<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Tbessenreither\FeatureFlagServiceClient\Dto\ClientInformationDto;
use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;


class ClientInformationDtoTest extends TestCase
{

	public function testProperties(): void
	{
		$dto = new ClientInformationDto(ClientInformationType::Warning, 'A warning message');
		$this->assertSame(ClientInformationType::Warning, $dto->getType());
		$this->assertSame('A warning message', $dto->getMessage());
	}

}
