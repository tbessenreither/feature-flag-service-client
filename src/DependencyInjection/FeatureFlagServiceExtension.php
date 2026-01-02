<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Tbessenreither\FeatureFlagService\Client\FeatureFlagClient;

/**
 * Extension for the FeatureFlagService bundle
 */
class FeatureFlagServiceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Register configuration parameters
        $container->setParameter('feature_flag_service.api_url', $config['api_url']);
        $container->setParameter('feature_flag_service.api_key', $config['api_key']);
        $container->setParameter('feature_flag_service.timeout', $config['timeout']);
        $container->setParameter('feature_flag_service.cache_enabled', $config['cache_enabled']);
        $container->setParameter('feature_flag_service.cache_ttl', $config['cache_ttl']);

        // Load services configuration
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');
    }
}
