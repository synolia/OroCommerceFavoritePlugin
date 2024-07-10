<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Layout\DataProvider;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessor;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteDataProvider
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly TokenAccessor $tokenAccessor
    ) {
    }

    public function getProductFavorite(Product|int $product): bool
    {
        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();

        if (!$user instanceof CustomerUser || !$organization instanceof Organization) {
            return false;
        }

        $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
            'product' => $product,
            'customerUser' => $user,
            'organization' => $organization,
        ]);

        return $favorite instanceof Favorite;
    }
}
