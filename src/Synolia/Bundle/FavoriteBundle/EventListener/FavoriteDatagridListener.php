<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Oro\Bundle\DataGridBundle\Event\BuildAfter;
use Oro\Bundle\SearchBundle\Datagrid\Datasource\SearchDatasource;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class FavoriteDatagridListener
{
    public function __construct(
        protected EntityManager $entityManager,
        protected AclHelper $aclHelper
    ) {
    }

    /**
     * @throws NotSupported
     */
    public function onBuildAfter(BuildAfter $event): void
    {
        $datasource = $event->getDatagrid()->getDatasource();
        if (!$datasource instanceof SearchDatasource) {
            return;
        }

        /** @var FavoriteRepository $repo */
        $repo = $this->entityManager->getRepository(Favorite::class);

        $favProductIds = $repo->findAllProductIdsFilteredByAcl($this->aclHelper);

        if (empty($favProductIds)) {
            $favProductIds = [0];
        }

        $datasource
            ->getSearchQuery()
            ->getCriteria()->where(Criteria::expr()->in('integer.system_entity_id', $favProductIds));
    }
}
