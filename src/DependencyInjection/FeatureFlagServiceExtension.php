<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Tbessenreither\FeatureFlagService\Client\FeatureFlagClient;
use Tbessenreither\FeatureFlagService\Client\FeatureFlagClientInterface;

/**
 * Extension for the FeatureFlagService bundle
 */
class FeatureFlagServiceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Register the FeatureFlagClient service
        $definition = new Definition(FeatureFlagClient::class);
        $definition->setArguments([
            $config['api_url'],
            $config['api_key'],
            $config['timeout'],
            $config['cache_enabled'],
            $config['cache_ttl'],
        ]);
        $definition->setPublic(false);
        $definition->setAutowired(true);
        $definition->setAutoconfigured(true);

        $container->setDefinition(FeatureFlagClient::class, $definition);

        // Create an alias for the interface
        $container->setAlias(FeatureFlagClientInterface::class, FeatureFlagClient::class);
    }
}
