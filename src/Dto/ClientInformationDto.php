<?php declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Dto;

use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;


class ClientInformationDto
{

	public function __construct(
		public readonly ClientInformationType $type,
		public readonly string $message,
	) {
	}

	public function getType(): ClientInformationType
	{
		return $this->type;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

}