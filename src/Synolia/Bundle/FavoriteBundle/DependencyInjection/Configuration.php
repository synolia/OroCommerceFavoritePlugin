<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\DependencyInjection;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const string ROOT_NODE = 'synolia_favorite';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NODE);
        $rootNode = $treeBuilder->getRootNode();

        SettingsBuilder::append($rootNode, [
            'favorite_product_enable' => [
                'value' => true,
                'type' => 'boolean',
            ],
        ]);

        return $treeBuilder;
    }
}
