<?php

namespace Msports\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('msport_user');

        $rootNode
            ->children()
            ->scalarNode('default_login_background_image')
                ->isRequired(true)
            ->end()
            ->scalarNode('default_login_background_color')
                ->isRequired(true)
            ->end()
            ->arrayNode('firewall_templates')
            ->prototype('array')
                ->children()
                    ->scalarNode('login_template')
                        ->isRequired(true)
                    ->end()
                    ->scalarNode('login_path')
                        ->isRequired(true)
                    ->end()
                    ->scalarNode('login_check')
                        ->isRequired(true)
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
