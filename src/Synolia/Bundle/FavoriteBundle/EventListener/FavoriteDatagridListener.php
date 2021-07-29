<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecord;
use Oro\Bundle\DataGridBundle\Event\BuildBefore;
use Oro\Bundle\DataGridBundle\Extension\Formatter\Property\PropertyInterface;
use Oro\Bundle\SearchBundle\Datagrid\Event\SearchResultAfter;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteDatagridListener
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

    public function onResultAfter(SearchResultAfter $event)
    {
        /** @var ResultRecord[] $records */
        $records = $event->getRecords();
        if (\count($records) === 0) {
            return;
        }

        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();

        $favorites = $this->entityManager->getRepository(Favorite::class)
            ->getFavoritesProductsInSingleArray($user, $organization);

        foreach ($records as $record) {
            $productId = $record->getValue('id');

            $record->addData(['favorite' => 0]);

            if (in_array($productId, $favorites)) {
                $record->addData(['favorite' => 1]);
            }
        }
    }


    public function onBuildBefore(BuildBefore $event)
    {
        $config = $event->getConfig();

        $config->offsetAddToArrayByPath(
            '[properties]',
            [
                'favorite' => [
                    'type' => 'field',
                    'frontend_type' => PropertyInterface::TYPE_DECIMAL
                ]
            ]
        );
    }
}
