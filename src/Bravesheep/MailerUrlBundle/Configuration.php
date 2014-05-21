<?php

namespace Bravesheep\MailerUrlBundle;

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
        $rootNode = $treeBuilder->root('bravesheep_mailer_url');

        $rootNode
            ->children()
                ->arrayNode('urls')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('url')->isRequired()->end()
                            ->scalarNode('prefix')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

