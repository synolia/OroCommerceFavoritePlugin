<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\ProductBundle\Event\BuildResultProductListEvent;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class ProductListAddFavoriteEventListener
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly AclHelper $aclHelper
    ) {
    }

    public function onBuildResult(BuildResultProductListEvent $event): void
    {
        $records = $event->getProductData();

        if (0 === \count($records)) {
            return;
        }

        /** @var FavoriteRepository $favoriteRepo */
        $favoriteRepo = $this->entityManager->getRepository(Favorite::class);
        $favProductIds = $favoriteRepo->findAllProductIdsFilteredByAcl($this->aclHelper);

        foreach (array_keys($event->getProductData()) as $productId) {
            $productView = $event->getProductView($productId);
            $productView->set('favorite', false);

            if (\in_array($productId, $favProductIds, true)) {
                $productView->set('favorite', true);
            }
        }
    }
}
