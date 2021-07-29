<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Handler;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteAjaxHandler
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

    public function create(Product $product): array
    {
        $synoliaFavoriteRepository = $this->entityManager->getRepository(Favorite::class);

        $user = $this->tokenAccessor->getUser();
        $organization = $this->tokenAccessor->getOrganization();
        if (!$user instanceof CustomerUser || !$organization instanceof Organization) {
            return [
                'success' => false,
            ];
        }

        $synoliaFavorite = $synoliaFavoriteRepository
            ->findOneBy([
                'customerUser' => $user,
                'organization' => $organization,
                'product' => $product
            ]);

        if (!$synoliaFavorite) {
            $synoliaFavorite = new Favorite();
            $synoliaFavorite->setCustomerUser($user);
            $synoliaFavorite->setOrganization($organization);
            $synoliaFavorite->setProduct($product);

            $this->entityManager->persist($synoliaFavorite);
            $this->entityManager->flush();

            return [
                'success' => true,
                'status' => 'full',
                'message' => 'The product was mark as a favorite'
            ];
        } else {
            $this->entityManager->remove($synoliaFavorite);
            $this->entityManager->flush();

            return [
                'success' => true,
                'status' => 'empty',
                'message' => 'The product was remove from the favorites'
            ];
        }
    }
}
