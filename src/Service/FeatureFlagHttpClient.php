<?php declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tbessenreither\FeatureFlagServiceClient\Dto\ClientInformationDto;
use Tbessenreither\FeatureFlagServiceClient\Dto\FeatureFlagDto;
use Tbessenreither\FeatureFlagServiceClient\Enum\ClientInformationType;


class FeatureFlagHttpClient
{

	public function __construct(
		private readonly string $ffsApiUrl,
		private readonly string $ffsScope,
		private readonly string $ffsApiKey,
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface $logger,
		private readonly int $timeout = 5,
	) {
	}

	/**
	 * @return FeatureFlagDto[]
	 */
	public function fetchAll(): array
	{
		$content = $this->fetchFromApi(
			url: $this->createUrl($this->ffsApiUrl),
		);

		$contentAsArray = json_decode($content, true, 25, JSON_THROW_ON_ERROR);
		if (!is_array($contentAsArray)) {
			throw new Exception('Invalid response format. Expected an array of feature flags.');
		}

		$featureFlagDtos = [];
		foreach ($contentAsArray as $flagData) {
			if (is_array($flagData)) {
				$featureFlagDtos[] = new FeatureFlagDto(
					scope: $flagData['scope'] ?? '',
					key: $flagData['key'] ?? '',
					value: $flagData['value'] ?? '',
					enabled: $flagData['enabled'] ?? false,
					clientInformation: null,
				);
			}
		}

		return $featureFlagDtos;
	}

	public function fetchOne(string $keyPath): FeatureFlagDto
	{
		$content = $this->fetchFromApi(
			url: $this->createUrl($keyPath),
		);

		$flagData = json_decode($content, true, 25, JSON_THROW_ON_ERROR);
		if (!is_array($flagData)) {
			throw new Exception("Invalid response format. Expected a feature flag array for '$keyPath'.");
		}

		$featureFlagDto = new FeatureFlagDto(
			scope: $flagData['scope'] ?? '',
			key: $flagData['key'] ?? '',
			value: $flagData['value'] ?? '',
			enabled: $flagData['enabled'] ?? false,
			clientInformation: isset($flagData['clientInformation']) && $flagData['clientInformation'] !== null ? new ClientInformationDto(
				type: ClientInformationType::from($flagData['clientInformation']['type'] ?? 'information'),
				message: $flagData['clientInformation']['message'] ?? '',
			) : null,
		);

		if ($featureFlagDto->getClientInformation() !== null) {
			if ($featureFlagDto->getClientInformation()->getType() === ClientInformationType::Error) {
				$this->logger->error('Feature flag "' . $keyPath . '" has client information: ' . $featureFlagDto->getClientInformation()->getMessage(), [
					'keyPath' => $keyPath,
					'information' => $featureFlagDto->getClientInformation(),
				]);
			} elseif ($featureFlagDto->getClientInformation()->getType() === ClientInformationType::Warning) {
				$this->logger->warning('Feature flag "' . $keyPath . '" has client information: ' . $featureFlagDto->getClientInformation()->getMessage(), [
					'keyPath' => $keyPath,
					'information' => $featureFlagDto->getClientInformation(),
				]);
			} else {
				$this->logger->info('Feature flag "' . $keyPath . '" has client information: ' . $featureFlagDto->getClientInformation()->getMessage(), [
					'keyPath' => $keyPath,
					'information' => $featureFlagDto->getClientInformation(),
				]);
			}
		}

		return $featureFlagDto;
	}

	private function createUrl(?string $keyPath = null): string
	{
		if ($keyPath === null) {
			return rtrim($this->ffsApiUrl, '/') . '/api/scope/' . urlencode($this->ffsScope) . '/feature_flags';
		} else {
			return rtrim($this->ffsApiUrl, '/') . '/api/scope/' . urlencode($this->ffsScope) . '/feature_flag/' . urlencode($keyPath);
		}
	}

	private function getHeaders(): array
	{
		return [
			'Accept: application/json',
			'Content-Type: application/json',
			'X-API-Key: ' . $this->ffsApiKey,
		];
	}

	private function fetchFromApi(string $url): string
	{
		$response = $this->httpClient->request('GET', $url, [
			'headers' => $this->getHeaders(),
			'timeout' => $this->timeout,
			'extra' => [
				'profiler_scope' => 'feature_flag_service',
			],
		]);
		$statusCode = $response->getStatusCode();
		if ($statusCode >= 200 && $statusCode < 300) {
			return $response->getContent();
		} else {
			throw new Exception('Error fetching data from Feature Flag Service API. HTTP Status Code: ' . $statusCode);
		}
	}

}