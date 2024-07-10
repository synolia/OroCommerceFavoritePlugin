<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteRepository extends EntityRepository
{
    public function findAllFilteredByAcl(AclHelper $aclHelper): array
    {
        return $aclHelper->apply($this->createQueryBuilder('f'))->getResult();
    }

    public function getFavoritesProductsCollection(CustomerUser $user, Organization $organization): array
    {
        return $this->createQueryBuilder('f')
            ->resetDQLPart('select')
            ->addSelect('IDENTITY(f.product) as product_id')
            ->andWhere('f.customerUser = :user')
            ->andWhere('f.organization = :organization')
            ->setParameters([
                'user' => $user,
                'organization' => $organization,
            ])
            ->getQuery()->getArrayResult();
    }

    public function getFavoritesProductsInSingleArray(CustomerUser $user, Organization $organization): array
    {
        $newArray = [];
        $favorites = $this->getFavoritesProductsCollection($user, $organization);

        foreach ($favorites as $favorite) {
            $newArray[] = $favorite['product_id'];
        }

        return $newArray;
    }

    public function findAllProductIdsFilteredByAcl(AclHelper $aclHelper): array
    {
        $ids = [];
        $favorites = $this->findAllFilteredByAcl($aclHelper);

        /** @var Favorite $favorite */
        foreach ($favorites as $favorite) {
            $ids[] = $favorite->getProduct()?->getId();
        }

        return $ids;
    }
}
