<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Layout\Extension;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Component\Layout\ContextConfiguratorInterface;
use Oro\Component\Layout\ContextInterface;

class FavoriteAwareContextConfigurator implements ContextConfiguratorInterface
{
    /** @var ConfigManager */
    private $configManager;

    /**
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    public function configureContext(ContextInterface $context): void
    {
        $context->getResolver()->setDefault('isFavoriteEnabled', true);
        $context->set(
            'isFavoriteEnabled',
            $this->configManager->get('synolia_favorite.favorite_product_enable')
        );
    }
}
