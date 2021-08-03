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
    /** @var entityManager */
    protected $entityManager;

    /** @var TokenAccessor */
    protected $tokenAccessor;

    public function __construct(
        entityManager $entityManager,
        tokenAccessor $tokenAccessor
    ) {
        $this->entityManager = $entityManager;
        $this->tokenAccessor = $tokenAccessor;
    }

    public function isProductFavorite(Product $product): bool
    {
        $user = $this->tokenAccessor->getUser();

        $organization = $this->tokenAccessor->getOrganization();
        if (!$user instanceof CustomerUser || !$organization instanceof Organization) {
            return false;
        }

        $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
            'product' => $product,
            'customerUser' => $user,
            'organization' => $organization
        ]);

        return $favorite instanceof Favorite;
    }
}
