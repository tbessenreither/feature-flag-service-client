<?php declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Dto;


class FeatureFlagDto
{

	public function __construct(
		private string $scope,
		private string $key,
		private string $value,
		private bool $enabled,
		private ?ClientInformationDto $clientInformation,
	) {
	}

	public function getScope(): string
	{
		return $this->scope;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function isEnabled(): bool
	{
		return $this->enabled;
	}

	public function getClientInformation(): ?ClientInformationDto
	{
		return $this->clientInformation;
	}

}
