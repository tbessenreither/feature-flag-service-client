<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Bundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tbessenreither\FeatureFlagServiceClient\Interface\FeatureFlagClientInterface;
use Tbessenreither\FeatureFlagServiceClient\Service\FeatureFlagClient;
use Tbessenreither\FeatureFlagServiceClient\Service\FeatureFlagHttpClient;


class FeatureFlagClientCompilerPass implements CompilerPassInterface
{

	public function process(ContainerBuilder $container): void
	{
		$this->processFeatureFlagClient($container);
		$this->processFeatureFlagHttpClient($container);
	}

	private function processFeatureFlagClient(ContainerBuilder $container): void
	{
		if (!$container->hasDefinition(FeatureFlagClient::class)) {
			$definition = new Definition(FeatureFlagClient::class);
			$definition->setAutowired(true);
			$definition->setAutoconfigured(true);
			$container->setDefinition(FeatureFlagClient::class, $definition);
		}
		// Alias interface to implementation
		$container->setAlias(FeatureFlagClientInterface::class, FeatureFlagClient::class)->setPublic(true);
	}

	private function processFeatureFlagHttpClient(ContainerBuilder $container): void
	{
		if ($container->hasDefinition(FeatureFlagHttpClient::class)) {
			$definition = new Definition(FeatureFlagHttpClient::class);
			$definition->setAutowired(true);
			$definition->setAutoconfigured(true);
			$definition->setArgument('$ffsApiUrl', '%env(FFS_API_URL)%');
			$definition->setArgument('$ffsScope', '%env(FFS_SCOPE)%');
			$definition->setArgument('$ffsApiKey', '%env(FFS_API_KEY)%');

			$definition->setArgument(
				'$client',
				new Reference('http_client.feature_flag_client')
			);

			// The rest will use autowiring defaults or can be set similarly if needed
			$container->setDefinition(FeatureFlagHttpClient::class, $definition);
		}
	}

}
