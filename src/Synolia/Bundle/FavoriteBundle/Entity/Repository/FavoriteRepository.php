<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;

class FavoriteRepository extends EntityRepository
{
    public function getFavoritesProductsCollection($user, $org): array
    {
        $qb = $this->createQueryBuilder('f');
        $qb
            ->resetDQLPart('select')
            ->select('IDENTITY(f.product) as product_id')
            ->andWhere('f.customerUser = :user')
            ->andWhere('f.organization = :org')
            ->setParameters(new ArrayCollection([
                new Parameter('user', $user),
                new Parameter('org', $org)
            ]));
        return $qb->getQuery()->getArrayResult();
    }

    public function getFavoritesProductsInSingleArray($user, $org): array
    {
        $newArray = [];

        $favorites = $this->getFavoritesProductsCollection($user, $org);

        foreach ($favorites as $favorite) {
            $newArray[] = $favorite['product_id'];
        }

        return $newArray;
    }
}
