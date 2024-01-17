<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Handler;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteAjaxHandler
{
    public function __construct(
        protected TokenAccessorInterface $tokenAccessor,
        protected EntityManager $entityManager,
        protected TranslatorInterface $translator
    ) {
    }

    public function create(Product $product): array
    {
        $synoliaFavoriteRepository = $this->entityManager->getRepository(Favorite::class);

        $user = $this->tokenAccessor->getUser();
        $organization = $user instanceof CustomerUser ? $user->getOrganization() : null;
        if (!$user instanceof CustomerUser || !$organization instanceof Organization) {
            return [
                'success' => false,
                'status' => 'warning',
                'message' => $this->translator->trans(
                    'synolia_favorite_bundle.controller.logged_in'
                )
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
                'message' =>  $this->translator->trans('synolia_favorite_bundle.frontend.messages.favorite_on'),
            ];
        } else {
            $this->entityManager->remove($synoliaFavorite);
            $this->entityManager->flush();

            return [
                'success' => true,
                'status' => 'empty',
                'message' => $this->translator->trans('synolia_favorite_bundle.frontend.messages.favorite_off')
            ];
        }
    }
}
