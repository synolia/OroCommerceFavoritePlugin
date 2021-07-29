<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\DataGridBundle\Event\BuildAfter;
use Oro\Bundle\SearchBundle\Datagrid\Datasource\SearchDatasource;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteCustomerDatagridListener
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

    public function onBuildAfter(BuildAfter $event)
    {
        $datasource = $event->getDatagrid()->getDatasource();

        if (!$datasource instanceof SearchDatasource) {
            return;
        }

        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();

        $products = $this->entityManager->getRepository(Favorite::class)
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
