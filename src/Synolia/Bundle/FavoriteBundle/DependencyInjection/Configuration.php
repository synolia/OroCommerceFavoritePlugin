<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\DependencyInjection;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('synolia_favorite');

        SettingsBuilder::append($rootNode, [
            'favorite_product_enable' => [
                'value' => true,
                'type' => 'boolean',
            ],
        ]);

        return $treeBuilder;
    }
}
