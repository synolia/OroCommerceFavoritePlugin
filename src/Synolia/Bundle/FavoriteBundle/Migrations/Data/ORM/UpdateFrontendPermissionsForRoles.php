<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Migrations\Data\ORM;

use Doctrine\Persistence\ObjectManager;
use Oro\Bundle\CustomerBundle\Migrations\Data\ORM\AbstractMassUpdateCustomerUserRolePermissions;
use Oro\Bundle\CustomerBundle\Migrations\Data\ORM\LoadCustomerUserRoles;
use Oro\Bundle\CustomerBundle\Owner\Metadata\FrontendOwnershipMetadataProvider;
use Oro\Bundle\SecurityBundle\Owner\Metadata\ChainOwnershipMetadataProvider;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

/**
 * Update Favorite default permissions for predefined roles.
 */
class UpdateFrontendPermissionsForRoles extends AbstractMassUpdateCustomerUserRolePermissions
{
    public function getDependencies(): array
    {
        return [LoadCustomerUserRoles::class];
    }

    public function load(ObjectManager $manager): void
    {
        $aclManager = $this->getAclManager();
        if (!$aclManager->isAclEnabled()) {
            return;
        }

        $chainMetadataProvider = $this->getChainMetadataProvider();
        $chainMetadataProvider->startProviderEmulation(FrontendOwnershipMetadataProvider::ALIAS);

        $this->updateRoles($aclManager, $manager);

        $chainMetadataProvider->stopProviderEmulation();

        $aclManager->flush();
    }

    protected function getACLData(): array
    {
        return [
            'ROLE_FRONTEND_ADMINISTRATOR' => [
                'entity:' . Favorite::class => ['VIEW_SYSTEM', 'CREATE_SYSTEM', 'EDIT_SYSTEM', 'DELETE_SYSTEM'],
            ],
            'ROLE_FRONTEND_BUYER' => [
                'entity:' . Favorite::class => ['VIEW_BASIC', 'CREATE_BASIC', 'EDIT_BASIC', 'DELETE_BASIC'],
            ],
            'ROLE_FRONTEND_ANONYMOUS' => [
                'entity:' . Favorite::class => ['VIEW_NONE', 'CREATE_NONE', 'EDIT_NONE', 'DELETE_NONE'],
            ],
        ];
    }

    private function getChainMetadataProvider(): ChainOwnershipMetadataProvider
    {
        /** @phpstan-ignore-next-line  */
        return $this->container->get('oro_security.owner.metadata_provider.chain');
    }
}
