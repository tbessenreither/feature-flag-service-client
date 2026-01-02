<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagService\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for the FeatureFlagService bundle
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('feature_flag_service');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('api_url')
                    ->info('The URL of the feature flag service API')
                    ->defaultValue('http://localhost:8000')
                ->end()
                ->scalarNode('api_key')
                    ->info('API key for authentication')
                    ->defaultNull()
                ->end()
                ->integerNode('timeout')
                    ->info('Request timeout in seconds')
                    ->defaultValue(5)
                ->end()
                ->booleanNode('cache_enabled')
                    ->info('Enable caching of feature flags')
                    ->defaultTrue()
                ->end()
                ->integerNode('cache_ttl')
                    ->info('Cache TTL in seconds')
                    ->defaultValue(300)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
