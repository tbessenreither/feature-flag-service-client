<?php declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\Dto;


class FeatureFlagDto
{

	public function __construct(
		private string $scope,
		private string $key,
		private string $value,
		private bool $enabled,
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

}
