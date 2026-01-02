<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
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
            '$apiUrl' => $config['api_url'],
            '$apiKey' => $config['api_key'],
            '$timeout' => $config['timeout'],
            '$cacheEnabled' => $config['cache_enabled'],
            '$cacheTtl' => $config['cache_ttl'],
        ]);
        $definition->setPublic(false);
        $definition->setAutowired(true);
        $definition->setAutoconfigured(true);

        $container->setDefinition(FeatureFlagClient::class, $definition);

        // Create an alias for the interface
        $container->setAlias(FeatureFlagClientInterface::class, FeatureFlagClient::class);
    }
}
