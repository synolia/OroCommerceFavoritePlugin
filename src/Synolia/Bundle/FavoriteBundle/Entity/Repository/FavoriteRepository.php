<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteRepository extends EntityRepository
{
    public function findAllProductIdsFilteredByAcl(AclHelper $aclHelper): array
    {
        $ids = [];
        $favorites = $aclHelper->apply($this->createQueryBuilder('f'))->getResult();

        /** @var Favorite $favorite */
        foreach ($favorites as $favorite) {
            $ids[] = $favorite->getProduct()?->getId();
        }

        return $ids;
    }
}
