<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
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

    /** @var FavoriteRepository */
    protected $favoriteRepository;

    public function __construct(
        TokenAccessorInterface $tokenAccessor,
        FavoriteRepository $favoriteRepository
    ) {
        $this->tokenAccessor = $tokenAccessor;
        $this->favoriteRepository = $favoriteRepository;
    }

    public function onBuildAfter(BuildAfter $event): void
    {
        $datasource = $event->getDatagrid()->getDatasource();

        if (!$datasource instanceof SearchDatasource) {
            return;
        }

        $user = $this->tokenAccessor->getUser();
        if (!$user instanceof CustomerUser) {
            return;
        }

        $organization = $this->tokenAccessor->getOrganization();
        if (!$organization instanceof Organization) {
            return;
        }

        $products = $this->favoriteRepository
            ->getFavoritesProductsCollection($user, $organization);

        $ids = [];

        foreach ($products as $product) {
            $ids[] = $product['product_id'];
        }

        if (empty($ids)) {
            $ids = [0];
        }

        $datasource
            ->getSearchQuery()
            ->addWhere(Criteria::expr()->in('integer.product_id', $ids));
    }
}
