<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecord;
use Oro\Bundle\DataGridBundle\Event\BuildBefore;
use Oro\Bundle\DataGridBundle\Extension\Formatter\Property\PropertyInterface;
use Oro\Bundle\SearchBundle\Datagrid\Event\SearchResultAfter;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Symfony\Bundle\SecurityBundle\Security;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class FrontendProductFavoriteDatagridListener
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly AclHelper $aclHelper,
        private readonly Security $security
    ) {
    }

    public function onBuildBefore(BuildBefore $event): void
    {
        $config = $event->getConfig();

        $config->offsetAddToArrayByPath(
            '[properties]',
            [
                'favorite' => [
                    'type' => 'field',
                    'frontend_type' => PropertyInterface::TYPE_BOOLEAN,
                ],
            ],
        );
    }

    public function onResultAfter(SearchResultAfter $event): void
    {
        $records = $event->getRecords();
        if (0 === \count($records)) {
            return;
        }

        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        }

        /** @var FavoriteRepository $favoriteRepo */
        $favoriteRepo = $this->entityManager->getRepository(Favorite::class);
        $favProductIds = $favoriteRepo->findAllProductIdsFilteredByAcl($this->aclHelper);
        /** @var ResultRecord $record */
        foreach ($records as $record) {
            $productId = $record->getValue('id');
            $record->setValue('favorite', false);

            if (\in_array((int) $productId, $favProductIds, true)) {
                $record->setValue('favorite', true);
            }
        }
    }
}
