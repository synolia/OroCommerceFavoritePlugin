<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\DataGridBundle\Event\BuildAfter;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SearchBundle\Datagrid\Datasource\SearchDatasource;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class SynoliaFavoriteGridListener
{
    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(
        TokenAccessorInterface $tokenAccessor,
        EntityManager $entityManager
    ) {
        $this->tokenAccessor = $tokenAccessor;
        $this->entityManager = $entityManager;
    }

    public function onBuildAfter(BuildAfter $event): void
    {
        $datasource = $event->getDatagrid()->getDatasource();
        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();

        if (!$user instanceof CustomerUser || !$organization instanceof Organization || !$datasource instanceof SearchDatasource) {
            return;
        }

        /** @var FavoriteRepository $favoriteRepository */
        $favoriteRepository = $this->entityManager->getRepository(Favorite::class);
        $products =  $favoriteRepository->getFavoritesProductsInSingleArray($user, $organization);

        if (empty($products)) {
            $products = [0];
        }

        $datasource
            ->getSearchQuery()
            ->getCriteria()->where(Criteria::expr()->in('integer.system_entity_id', $products));
    }
}
