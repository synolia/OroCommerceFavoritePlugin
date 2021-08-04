<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\NavigationBundle\Event\ConfigureMenuEvent;
use Oro\Bundle\NavigationBundle\Utils\MenuUpdateUtils;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NavigationListener
{
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    /** @var ConfigManager */
    protected $configManager;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenAccessorInterface $tokenAccessor,
        ConfigManager $configManager
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenAccessor = $tokenAccessor;
        $this->configManager = $configManager;
    }

    public function onNavigationConfigure(ConfigureMenuEvent $event): void
    {
        if (!$this->tokenAccessor->hasUser()) {
            return;
        }
        $manageMenusItem = MenuUpdateUtils::findMenuItem($event->getMenu(), 'synolia_favorites_list');

        if (null !== $manageMenusItem && ($this->isFavoriteEnabled() == false)) {
            $manageMenusItem->setDisplay(false);
        }
    }

    public function isFavoriteEnabled(): bool
    {
        return $this->configManager->get('synolia_favorite.favorite_product_enable');
    }
}
