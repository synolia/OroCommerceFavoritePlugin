<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ProductBundle\Event\BuildQueryProductListEvent;
use Oro\Bundle\ProductBundle\Event\BuildResultProductListEvent;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessor;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class ProductListAddFavoriteEventListener
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private TokenAccessor $tokenAccessor,
    ) {
    }

    public function onBuildResult(BuildResultProductListEvent $event): void
    {
        $records = $event->getProductData();
        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();

        if (0 === \count($records)) {
            return;
        }

        /** @var FavoriteRepository $favoriteRepo */
        $favoriteRepo = $this->entityManager->getRepository(Favorite::class);
        $favorites = !$user instanceof CustomerUser || !$organization instanceof Organization ? [] : $favoriteRepo->getFavoritesProductsInSingleArray($user, $organization);

        foreach ($event->getProductData() as $productId => $data) {
            $productView = $event->getProductView($productId);
            $productView->set('favorite', in_array($productId, $favorites) ?? false);
        }
    }
}
