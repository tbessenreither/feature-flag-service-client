<?php

declare(strict_types=1);

namespace Tbessenreither\Bundle\FeatureFlagClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tbessenreither\FeatureFlagService\Interface\FeatureFlagClientInterface;
use Tbessenreither\FeatureFlagService\Service\FeatureFlagClient;


class FeatureFlagClientCompilerPass implements CompilerPassInterface
{

	public function process(ContainerBuilder $container): void
	{
		if (!$container->hasDefinition(FeatureFlagClient::class)) {
			$definition = new Definition(FeatureFlagClient::class);
			$definition->setAutowired(true);
			$definition->setAutoconfigured(true);
			$definition->setArgument('$ffsApiUrl', '%env(FFS_API_URL)%');
			$definition->setArgument('$ffsScope', '%env(FFS_SCOPE)%');
			$definition->setArgument('$ffsApiKey', '%env(FFS_API_KEY)%');
			// The rest will use autowiring defaults or can be set similarly if needed
			$container->setDefinition(FeatureFlagClient::class, $definition);
		}
		// Alias interface to implementation
		$container->setAlias(FeatureFlagClientInterface::class, FeatureFlagClient::class)->setPublic(true);
	}

}
