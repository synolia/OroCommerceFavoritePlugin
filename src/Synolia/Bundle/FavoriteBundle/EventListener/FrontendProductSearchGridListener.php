<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\EventListener;

use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecord;
use Oro\Bundle\DataGridBundle\Event\BuildBefore;
use Oro\Bundle\DataGridBundle\Extension\Formatter\Property\PropertyInterface;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SearchBundle\Datagrid\Event\SearchResultAfter;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository;

class FrontendProductSearchGridListener
{
    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    /** @var FavoriteRepository */
    private $favoriteRepository;

    public function __construct(
        TokenAccessorInterface $tokenAccessor,
        FavoriteRepository $favoriteRepository
    ) {
        $this->tokenAccessor = $tokenAccessor;
        $this->favoriteRepository = $favoriteRepository;
    }

    public function onResultAfter(SearchResultAfter $event): void
    {
        /** @var ResultRecord[] $records */
        $records = $event->getRecords();
        if (\count($records) === 0) {
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

        $favorites = $this->favoriteRepository
            ->getFavoritesProductsInSingleArray($user, $organization);

        foreach ($records as $record) {
            $productId = $record->getValue('id');

            $record->addData(['favorite' => 0]);

            if (in_array($productId, $favorites)) {
                $record->addData(['favorite' => 1]);
            }
        }
    }


    public function onBuildBefore(BuildBefore $event): void
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
